## About This Repository

a Laravel and Vue.js Livewire Inerbased web application for managing Insurance Claims for 2CA Business.

## Preview


https://github.com/user-attachments/assets/b71feb52-239e-484e-8b4a-99b194e4aa4f



## Logging In

Following credentials can be used to log in the system

<table>
    <thead>
       <tr>
            <th>Sl</th>
            <th>Role</th>
            <th>Email Address</th>
            <th>Password</th>
       </tr> 
    </thead>
    <tbody>
        <tr>
            <td>01</td>
            <td>Admin</td>
            <td>admin@admin.com</td>
            <td>password</td>
        </tr>
    </tbody>
    <tbody>
        <tr>
            <td>02</td>
            <td>Broker</td>
            <td>broker@broker.com</td>
            <td>password</td>
        </tr>
    </tbody>
        <tbody>
        <tr>
            <td>03</td>
            <td>Client</td>
            <td>client@client.com</td>
            <td>password</td>
        </tr>
    </tbody>
</table>
<br>

## Initial Permissions:
- Admin is granted all the permissions.
- Broker is granted (claims-view, claims-edit, attachments-view)
- Client is granted (claims-view, claims-create, attachments-view, attachments-create)

## Installation

First download this repository. Navigate to root of the project and then

<pre>
    <code>composer install</code>
    <code>npm install</code>
</pre>

Copy the contents of .env.example to .env file. Fill up the database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD) according to your database, Fill up you SMTP mailer config (MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM_ADDRESS).

At the root of your project run the following commands on terminal sequentially.

<pre>
    <code>php artisan key:generate</code>
    <code>php artisan migrate</code>
    <code>php artisan db:seed</code>
</pre>

This will store all the default data into the database. Then compile the assets and run development server by

<pre>
    <code>npm run dev</code>
</pre>

Finally initiate your server on a new terminal

<pre>
    <code>php artisan serve</code>
</pre>
