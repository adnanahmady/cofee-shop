export default {
  response: {
    status: 422,
    data: {
      message: 'No user exists with "john@shop.com" email',
      errors: {
        email: ['No user exists with "john@shop.com" email'],
      },
    },
  },
};
