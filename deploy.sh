#!/bin/bash
##### Constants #####

PROJECT_NAME='PodR'

# The location of the PodR project you want to deploy.
PROJECT_LOCAL_PATH=/home/user/projects/PodR_Web


##### Functions #####
function prep()
{
    echo "Preparing for Deploy..."
    php $PROJECT_LOCAL_PATH/app/console cache:clear --env=prod --no-debug
    php $PROJECT_LOCAL_PATH/app/console assetic:dump --env=prod --no-debug
}


######## Run ########
echo
echo "************* Deploying $PROJECT_NAME *************"
echo

# Prepare for Deploy by clearing the local cache and dumping assetic assets.
prep

echo "Deploy complete."
echo
exit 0