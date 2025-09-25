import dotenv from 'dotenv';
import path from 'node:path';
import express from 'express';
import session from 'express-session';
import SQLiteStoreFactory from 'connect-sqlite3';
import helmet from 'helmet';
import compression from 'compression';
import morgan from 'morgan';
import csrf from 'csurf';
import rateLimit from 'express-rate-limit';
import lusca from 'lusca';
import { fileURLToPath } from 'node:url';

import { initDatabase } from './utils/db.js';
import authRouter from './routes/auth.js';
import dashboardRouter from './routes/dashboard.js';

dotenv.config();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();

// Views
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Trust proxy if behind reverse proxy
if (process.env.TRUST_PROXY === 'true') {
  app.set('trust proxy', 1);
}

// Security headers
app.use(helmet({
  contentSecurityPolicy: false,
}));

// Compression and logging
app.use(compression());
app.use(morgan('dev'));

// Global rate limit (broad safety net)
app.use(rateLimit({
  windowMs: 15 * 60 * 1000,
  max: 300,
  standardHeaders: true,
  legacyHeaders: false,
}));

// Body parsing
app.use(express.urlencoded({ extended: false }));
app.use(express.json());

// Static
app.use('/public', express.static(path.join(__dirname, 'public'), { maxAge: '1d' }));

// Sessions
const SQLiteStore = SQLiteStoreFactory(session);
const sessionSecret = process.env.SESSION_SECRET || 'change-me-dev-secret';
app.use(session({
  store: new SQLiteStore({ db: 'sessions.sqlite', dir: path.join(__dirname, '..', 'data') }),
  name: 'sid',
  secret: sessionSecret,
  resave: false,
  saveUninitialized: false,
  cookie: {
    httpOnly: true,
    sameSite: 'lax',
    secure: process.env.COOKIE_SECURE === 'true',
    maxAge: 1000 * 60 * 60 * 2, // 2 hours
  },
}));

// CSRF (cookie-less, session based token)
app.use(csrf());

// Additional protections (frameguard, xss)
app.use(lusca.xframe('SAMEORIGIN'));
app.use(lusca.xssProtection(true));

// Set locals
app.use((req, res, next) => {
  res.locals.csrfToken = req.csrfToken ? req.csrfToken() : '';
  res.locals.currentUser = req.session.user || null;
  next();
});

// Initialize DB and schema
initDatabase();

// Routes
app.use('/', authRouter);
app.use('/dashboard', dashboardRouter);

app.get('/', (req, res) => {
  if (req.session.user) return res.redirect('/dashboard');
  return res.redirect('/login');
});

// 404
app.use((req, res) => {
  res.status(404).render('404', { title: 'Not Found' });
});

// Error handler
// eslint-disable-next-line no-unused-vars
app.use((err, req, res, next) => {
  const isCsrf = err.code === 'EBADCSRFTOKEN';
  if (isCsrf) {
    return res.status(403).render('error', { title: 'Security Error', message: 'Invalid CSRF token.' });
  }
  const status = err.status || 500;
  const message = process.env.NODE_ENV === 'production' ? 'Internal Server Error' : err.message;
  return res.status(status).render('error', { title: 'Error', message });
});

const port = process.env.PORT || 3000;
app.listen(port, () => {
  // eslint-disable-next-line no-console
  console.log(`Server listening on http://localhost:${port}`);
});

