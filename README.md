# symfony-auth
1 - Get project
``` git clone https://github.com/oipnet-mentorat/symfony-auth.git```

2 - Install dependencies
``` composer update ```

3 - Create .env
``` cp .env.dist .env```

4 - Create database
``` bin/console doctrine:database:create```

5 - Run migrations
``` bin/console doctrine:migrations:migrate ```

6 - Install and Run maildev
``` sudo npm install -g maildev && maildev ```

7 - Run server and Enjoy
``` bin/console server:run ```
