import { Router } from 'express';
import { z } from 'zod';
import { v4 as uuidv4 } from 'uuid';
import argon2 from 'argon2';
import { getDb } from '../utils/db.js';
import rateLimit from 'express-rate-limit';

const loginLimiter = rateLimit({
  windowMs: 15 * 60 * 1000,
  max: 20,
  standardHeaders: true,
  legacyHeaders: false,
});

const registerLimiter = rateLimit({
  windowMs: 60 * 60 * 1000,
  max: 10,
  standardHeaders: true,
  legacyHeaders: false,
});

const router = Router();

const registerSchema = z.object({
  email: z.string().email().min(5).max(256),
  password: z.string().min(8).max(128),
  confirmPassword: z.string().min(8).max(128),
}).refine((data) => data.password === data.confirmPassword, {
  message: 'Passwords do not match',
  path: ['confirmPassword'],
});

const loginSchema = z.object({
  email: z.string().email(),
  password: z.string().min(1),
});

function redirectIfAuthenticated(req, res, next) {
  if (req.session.user) return res.redirect('/dashboard');
  return next();
}

router.get('/register', redirectIfAuthenticated, (req, res) => {
  res.render('register', { title: 'Register', errors: {}, values: {} });
});

router.post('/register', registerLimiter, async (req, res) => {
  const parse = registerSchema.safeParse(req.body);
  if (!parse.success) {
    const errors = Object.fromEntries(parse.error.issues.map(i => [i.path.join('.'), i.message]));
    return res.status(400).render('register', { title: 'Register', errors, values: req.body });
  }
  const { email, password } = parse.data;
  const db = getDb();
  const existing = db.prepare('SELECT id FROM users WHERE email = ?').get(email.toLowerCase());
  if (existing) {
    return res.status(409).render('register', { title: 'Register', errors: { email: 'Email already in use' }, values: { email } });
  }
  const passwordHash = await argon2.hash(password, { type: argon2.argon2id, memoryCost: 19456, timeCost: 2, parallelism: 1 });
  const id = uuidv4();
  db.prepare('INSERT INTO users (id, email, password_hash) VALUES (?, ?, ?)').run(id, email.toLowerCase(), passwordHash);
  req.session.user = { id, email };
  return res.redirect('/dashboard');
});

router.get('/login', redirectIfAuthenticated, (req, res) => {
  res.render('login', { title: 'Login', errors: {}, values: {} });
});

router.post('/login', loginLimiter, async (req, res) => {
  const parse = loginSchema.safeParse(req.body);
  if (!parse.success) {
    const errors = Object.fromEntries(parse.error.issues.map(i => [i.path.join('.'), i.message]));
    return res.status(400).render('login', { title: 'Login', errors, values: req.body });
  }
  const { email, password } = parse.data;
  const db = getDb();
  const user = db.prepare('SELECT id, email, password_hash FROM users WHERE email = ?').get(email.toLowerCase());
  if (!user) {
    return res.status(401).render('login', { title: 'Login', errors: { email: 'Invalid email or password' }, values: { email } });
  }
  const ok = await argon2.verify(user.password_hash, password);
  if (!ok) {
    return res.status(401).render('login', { title: 'Login', errors: { email: 'Invalid email or password' }, values: { email } });
  }
  req.session.user = { id: user.id, email: user.email };
  return res.redirect('/dashboard');
});

router.post('/logout', (req, res) => {
  req.session.destroy(() => {
    res.clearCookie('sid');
    res.redirect('/login');
  });
});

export default router;

