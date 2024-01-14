// validation/Student.js
import { body, param,check } from 'express-validator';
let StudentValidation = {}

StudentValidation.createValidation = [
  body('nama').notEmpty().withMessage('Nama is Required'),
  body('nim').notEmpty().withMessage('NIM is Required'),
  body('email').isEmail().withMessage('Email is Required'),
  body('jurusan').notEmpty().withMessage('Jurusan is Required'),
];

StudentValidation.updateValidation = [
  body('nama').optional().notEmpty().withMessage('Nama is required'),
  body('nim').optional().notEmpty().withMessage('NIM harus diisi'),
  body('email').optional().isEmail().withMessage('invalid email'),
  body('jurusan').optional().notEmpty().withMessage('Jurusan harus diisi'),
];

StudentValidation.deleteValidation = [
  param('id').notEmpty().withMessage('ID Student harus diisi'),
];

StudentValidation.validate = (req, res, next) => {
  const errors = validationResult(req);
  if (errors.isEmpty()) {
    return next();
  }
  return res.status(422).json({ errors: errors.array() });
};


export default StudentValidation;
