## About This Repository

a Laravel and Vue.js Livewire Inerbased web application for managing Insurance Claims for 2CA Business.

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

Initailly only Admin is granted all the permissions. All other role has no permissions. To add permissions to any other role, first log in as super admin, then go to the roles page. Assign necessary permissions to the role. Then come to the users page and update users roles. One user can have multiple roles. Overall user permissions will be the collection of all roles permissions combined. admin role is readonly(can not be edited or deleted). This role can not be applied to any other user. If a new permission is created, updated or deleted, it will be automatically applied to admin.

## Installation

First download this repository. Navigate to root of the project and then

<pre>
    <code>composer install</code>
    <code>npm install</code>
</pre>

Copy the contents of .env.example to .env file. Fill up the database credentials(DB_DATABASE, DB_USERNAME, DB_PASSWORD) according to your database. At the root of your project run the following commands on terminal sequentially.

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
