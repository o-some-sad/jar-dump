

# some configuration needs to be done to be able to run this project properly 

1. enable rewrite module for apache2
```sh
sudo a2enmod rewrite
sudo systemctl restart apache2 # apply the changes
```

2. modify `/etc/apache2/apache2.conf` file
under the `<Directory /var/www/>` directive, you'll need to modify this value
```diff
- AllowOverride None
+ AllowOverride All
```
then run this command again
```sh
sudo systemctl restart apache2 # apply the changes
```