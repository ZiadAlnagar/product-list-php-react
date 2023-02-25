module.exports = {
  env: {
    'browser': true,
    'es2021': true,
    'jest/globals': true,
    'cypress/globals': true,
  },
  extends: ['eslint:recommended', 'plugin:react/recommended', 'airbnb', 'prettier'],
  overrides: [],
  parserOptions: {
    ecmaVersion: 'latest',
    ecmaFeatures: {
      jsx: true,
    },
    sourceType: 'module',
  },
  plugins: ['react', 'jest', 'cypress'],
  settings: {
    react: {
      version: 'detect',
    },
  },
  rules: {
    'no-alert': 0,
    'no-unused-vars': 0,
    'no-nested-ternary': 0,
    'no-param-reassign': 0,
    'no-use-before-define': 0,
    'prefer-destructuring': 0, // not pref for redux useSelector
    'react/button-has-type': 0,
    'react/jsx-props-no-spreading': 0,
    'consistent-return': 0,
    'no-return-assign': [2, 'except-parens'],
    'quote-props': [2, 'consistent-as-needed'],
    'no-else-return': [2, { allowElseIf: true }],
    'no-unused-expressions': [2, { allowShortCircuit: true, allowTernary: true }],
    'import/no-extraneous-dependencies': [
      'error',
      { devDependencies: ['**/*.config.js', '**/*.test.js'] },
    ],
    'jsx-quotes': [0, 'prefer-single'],
    'react/no-array-index-key': 0,
    'react/prop-types': 0,
    'react/react-in-jsx-scope': 0,
    'react/jsx-filename-extension': 0,
    'react/jsx-one-expression-per-line': 0,
    'react/function-component-definition': [2, { namedComponents: 'arrow-function' }],
  },
};

// 'no-shadow': 0,
// 'no-console': 0,
// 'import/named': 0,
// 'consistent-return': 0,
// 'no-underscore-dangle': 0,
// 'no-else-return': [2, { allowElseIf: true }],
