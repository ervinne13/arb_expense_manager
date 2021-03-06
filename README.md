# ARB Calls Expense Manager Demo Application

## Getting Started

To boot up the application make sure the latest docker version (`20.10.17` as of writing) and docker compose version (`v2.6.0`) is installed.

Create a new `.env` file based on the `.env.example`. Update values as you like.

Run: 

```
sudo ./firstrun.sh
```

Note: if `firstrun.sh` is not executable, simply add permission by:

```
chmod +x ./firstrun.sh
```

This should create a new network bridge, an entry in your /etc/hosts, and create a `arb-443.conf` file in your nginx conf.d folder to use as virtual hosting.

Now once you run:

```
docker compose up -d
```

Note that this uses the latest docker compose plugin, notciable by using `docker compose` instead of `docker-compose` command. If you don't have it yet, just install it via:

```
sudo apt-get update
sudo apt-get install docker-compose-plugin
```

The commands `composer install`, `npm i`, and `php artisan key:generate` are all automatically executed upon docker compose.

Then you can just access the application via the `NGINX_ALIAS` you set in the browser.

## Initializing the app

To initialize the app and do a migrate refresh seed, just do:

```
docker exec -it expense-man-laravel php artisan migrate:refresh --seed
```

## Passport Creds & User Access

You'll likely need to have passport setup initially and create at least one user. To do this simply just:

```
docker exec -it expense-man-laravel php artisan passport:install
```

copy the client id 2 and it's secret, and put it to laravel_backend/.env

it should look something like:

```
Client ID: 2
Client secret: somegibberishhere
```

Make sure to take Client id 2 or whichever is the password grant client based on the name.

You also need to refresh the frontend if you're not doing an npm watch:

```
docker exec -it expense-man-laravel npm run dev
```
or
```
docker exec -it expense-man-laravel npm run watch
```


Then finally:

```
docker exec -it expense-man-laravel php artisan arb:new-admin
```

This will prompt you for the first admin details so you may log in to the system.

TODO: It would be nice if this could be included in the `firstrun.sh`. However, the client id and secret are saved in the database so it's not exactly something ideal to be automated unfortunately. Further research must be done here.

## Running the Application

Check which host you put in your `NGINX_ALIAS` and visit it in the browser. The application should now be running there. Congratulations!

===========================================================================

## After First Setup

Once you've set things up, you only need to run a few things to view the application again:

```
docker compose up -d
docker exec -it expense-man-laravel npm run watch
```

## Setting Up Hot Reloading Local Frontend

You may inspect the generated `arb-443.conf` in the nginx folder to see that the js and css assets are directed to the 8079 port. This should be the location of when you do an `npm run watch` to enable hot reloading. So now we open a new terminal tab and execute:

```
docker exec -it expense-man-laravel npm run watch
```

You may also execute `npm run dev` instead if you don't need a hot reload.

## Resetting The database

The way the mysql container is setup is that even if you do a `docker compose down` the data inside it won't go away. This is because we've set a volume for it so that we can freely do compose downs and up. However, if you updated your credentials in the envs, this wont take effect because of this setup. We can clear the database by including the volumes when we compose down with the command: `docker compose down -v`.

## More Information

You should be good even without reading more, but if you're not familiar with nginx virtual hosting and want to understand wth am I doing here, you can read more information as to why the application is set up this way below. I just personally dislike using exposed ports as I deal with a lot of applications simultaneously, IPs with multiple network bridges feels more future proofed and would cause less conflict when working with multiple projects at once. Plus cookies between different applications are guaranteed to not interfere with each other in case you use the same ports for applications. **Google authentication is not possible with just ports too.** It also opens up the possibility of using local, self-signed ssl, the list of benefit goes on.

## Docker Setup

Note that we do not expose the application ports, instead, we have nginx exposed and the reverse proxy is doing the routing inside the docker containers. We use IP aliases to access NGINX this way, hence the value you set in `NGINX_ALIAS` will be used as the address in the browser to access the application. Please allow insecure https as we only use self-signed certificates to simulate https. This will make it easier later on to add stuff like google authentication and anything that has dependency on whitelisting an address, adding other services like elasticsearch, plus it's closest to production behavior so we don't suddenly get errors that work on local but not in staging and prod.

## The environment variables

### `BRIDGE_NETWORK` & `BRIDGE_NETWORK_ADDRESS`

The `BRIDGE_NETWORK` will be the name of the automatically generated bridge network this application will use so we can house IPs. The `BRIDGE_NETWORK_ADDRESS` is simply the first digits in the IPs to be used [see here](https://www.informit.com/articles/article.aspx?p=433329&seqNum=5).

Example, if you have `BRIDGE_NETWORK_ADDRESS` set to 128, then you'll set the next IP based env variables to somethings like 128.0.0.2 onwards.


### `NGINX_ALIAS` and `NGINX_IP`

The alias is the virtual host to be used to access the application in the browser. Set the IP to whatever valid IP in the subnet.

### `DB_X` Variables

This is your usual database variables. Values here are the values you can use to access mysql via workbench or whichever database viewer you choose.

## Debugging

### Logs

Notice `.env.tmpl` and the created `.env`. I used stderr instead of stack as a log channel so that it's actually easier to live monitor logs as we develop. Simply run:

```
docker logs -f expense-man-laravel
```

to display the logs in real time.

### Laravel ENV

On the topic of `.env`, the file was automatically created when you run `firstrun.sh`.

### TODO

https://www.npmjs.com/package/vue-dashboard-vd