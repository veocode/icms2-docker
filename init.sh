#!/bin/bash
ICMS_REPO="https://github.com/instantsoft/icms2.git"
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

if [[ $1 == "clear" ]]; then
    echo "Cleaning installation..."
    rm -rf $DIR/icms2
    mkdir $DIR/icms2
    echo "" > $DIR/icms2/.gitkeep
    rm -rf $DIR/mysql/db/*
    echo "" > $DIR/mysql/db/.gitkeep    
    cp $DIR/vendor/compose.yml $DIR/docker-compose.yml
    exit 0    
fi

declare -A envs 
envs[VERSION]=2.13.1
envs[HTTP_PORT]=80
envs[MYSQL_DATABASE]=icmsdb
envs[MYSQL_USER]=icmsdb
envs[MYSQL_PASSWORD]=secret
envs[MYSQL_ROOT_PASSWORD]=rootsecret
envs[PHPMYADMIN_INSTALL]=y
envs[PHPMYADMIN_PORT]=8001

declare -A prompts
prompts[VERSION]="InstantCMS version to install"
prompts[HTTP_PORT]="Web-server Port"
prompts[MYSQL_DATABASE]="MySQL Database"
prompts[MYSQL_USER]="MySQL User"
prompts[MYSQL_PASSWORD]="MySQL User Password"
prompts[MYSQL_ROOT_PASSWORD]="MySQL Root Password"
prompts[PHPMYADMIN_INSTALL]="Install phpMyAdmin? (y/n)"
prompts[PHPMYADMIN_PORT]="phpMyAdmin Port"

order=(VERSION HTTP_PORT MYSQL_DATABASE MYSQL_USER MYSQL_PASSWORD MYSQL_ROOT_PASSWORD PHPMYADMIN_INSTALL PHPMYADMIN_PORT)
if [[ $1 == "deploy" ]]; then
    order=(HTTP_PORT MYSQL_DATABASE MYSQL_USER MYSQL_PASSWORD MYSQL_ROOT_PASSWORD PHPMYADMIN_INSTALL PHPMYADMIN_PORT)
fi

PHPMYADMIN_INSTALL=y

echo ""
echo -e "\e[96mWelcome to icms2-docker Installation Wizard\e[39m"
echo "Please answer the questions to initialise your installation"
echo ""

for key in "${order[@]}"; do 
    if [[ $key == "PHPMYADMIN_PORT" && $PHPMYADMIN_INSTALL != "y" ]]; then
        continue
    fi
    default=${envs[$key]}
    prompt=${prompts[$key]}
    read -p "    $prompt "$'\e[2m['"$default"$']\e[22m: ' answer
    answer=${answer:-$default}
    envs[$key]=$answer
    if [ $key == "PHPMYADMIN_INSTALL" ]; then
        PHPMYADMIN_INSTALL=$answer
    fi
done

echo ""

if [[ $1 != "deploy" ]]; then
    echo "Cleaning installation..."
    rm -rf $DIR/icms2
    rm -rf $DIR/mysql/db/*
    echo '' > $DIR/mysql/db/.gitkeep    
fi

echo "Saving configuration..."
cp $DIR/vendor/compose.yml $DIR/docker-compose.yml
rm -f $DIR/.env
for key in "${order[@]}"; do 
    if [[ $key == "PHPMYADMIN_PORT" && $PHPMYADMIN_INSTALL != "y" ]]; then
        continue
    fi
    echo "$key=${envs[$key]}" >> $DIR/.env
done
if [[ $PHPMYADMIN_INSTALL == "y" ]]; then
    cat $DIR/vendor/phpmyadmin.yml >> $DIR/docker-compose.yml
fi

if [[ $1 != "deploy" ]]; then
    VERSION="${envs[VERSION]}"

    echo "Downloading InstantCMS v$VERSION..."
    git clone -q --branch $VERSION $ICMS_REPO || { 
        echo 'Failed to download. Bad version?' ; exit 1; 
    }

    echo "Cleaning repository stuff..."
    rm -rf $DIR/icms2/.git
    rm -rf $DIR/icms2/.github
    rm -rf $DIR/icms2/.gitignore
    rm -f $DIR/icms2/ISSUE_TEMPLATE.md
    rm -f $DIR/icms2/README.md
    rm -f $DIR/icms2/README_RU.md
    rm -f $DIR/icms2/LICENSE
fi

echo "Setting up permissions..."
find $DIR/icms2/ -type f -exec chmod 644 {} \;
find $DIR/icms2/ -type d -exec chmod 755 {} \;
chmod -R 777 $DIR/icms2/upload
chmod -R 777 $DIR/icms2/cache
chmod 777 $DIR/icms2/system/config

echo "Starting Docker..."
docker-compose up -d

echo "Done!"
echo " "
