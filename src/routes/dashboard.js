import { Router } from 'express';

function requireAuth(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/login');
  }
  return next();
}

const router = Router();

router.get('/', requireAuth, (req, res) => {
  res.render('dashboard', { title: 'Dashboard' });
});

export default router;

