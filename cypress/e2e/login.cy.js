/// <reference types="cypress" />

describe('Login Page', () => {
    beforeEach(() => {
        cy.visit('/'); // Make sure the URL is correct
    });

    it('should render the login form', () => {
        cy.get('h1').should('contain', 'Sign in to your account');
        cy.contains('label', 'Email').next('input').should('be.visible');
        cy.contains('label', 'Password').next('input').should('be.visible');
        cy.get('button').should('contain', 'Sign in');
    });

    it('should allow a user to login with correct credentials', () => {
        cy.intercept('POST', '/api/login', (req) => {
            req.reply((res) => {
                console.log('Intercepted request:', req);
                console.log('Intercepted response:', res);
                res.send({ fixture: 'user.json' });
            });
        }).as('loginRequest');
    
        cy.get('input').first().type('admin@admin.com'); // Assuming the first input is for email
        cy.get('input').eq(1).type('password'); // Assuming the second input is for password
        cy.get('button').contains('Sign in').click();
    
        cy.wait('@loginRequest').its('response.statusCode').should('eq', 200);

        // Log the current URL
        cy.url().then((url) => {
            cy.log('Current URL:', url);
        });

        // Assert that the user is redirected to the claims page with increased timeout
        cy.url().should('include', '/claims');
    });
});