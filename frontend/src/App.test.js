import {
  render,
  screen
} from '@testing-library/react';
import App from './App';

test('It should show navbar', () => {
  render(<App />);

  const element = screen.getByText('Rock Star');

  expect(element.getAttribute('class')).toContain('navbar');
});

test('The programmer name should get shown', () => {
  render(<App />);

  const element = screen.getByText(/Adnan ahmady/);

  expect(element).toBeInTheDocument();
});

test('The application name should be visible', () => {
  render(<App />);

  const element = screen.getByText(/Rock Star Coffee Shop/);

  expect(element).toBeInTheDocument();
});
