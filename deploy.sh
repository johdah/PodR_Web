# deploy.sh
sudo php app/console cache:clear --env=prod
sudo php app/console assetic:dump --env=prod --no-debug
sudo chmod 777 -R app/cache