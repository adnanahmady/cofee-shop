import {
  render,
  screen
} from '@testing-library/react';
import ErrorMessage from '.';

it('should show given message when its presented', () => {
  const message = new Date().toString();
  render(<ErrorMessage message={message} />);

  const element = screen.getByText(message);

  expect(element).toBeInTheDocument();
});

it ('should show nothing when message is not passed to', () => {
  const {container} = render(<ErrorMessage />);

  expect(container).toBeEmptyDOMElement();
});