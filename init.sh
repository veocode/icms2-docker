#!/bin/bash
ICMS_REPO="https://github.com/instantsoft/icms2.git"
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
MODE=${1:-"install"}

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

PHPMYADMIN_INSTALL=y

clear_installation() {
    echo "Cleaning installation..."
    rm -rf $DIR/icms2
    rm -rf $DIR/mysql/db
    mkdir $DIR/icms2
    mkdir $DIR/mysql/db
    echo "" > $DIR/icms2/.gitkeep    
    echo "" > $DIR/mysql/db/.gitkeep    
    cp $DIR/vendor/compose.yml $DIR/docker-compose.yml   
}

run_wizard() {
    echo ""
    echo -e "\e[96mWelcome to icms2-docker Installation Wizard\e[39m"
    echo "Please answer the questions to initialise your installation"
    echo ""

    for key in "${order[@]}"; do 
        if [[ $key == "VERSION" && $MODE != "install" ]]; then
            continue
        fi    
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
}

save_config() {
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
}

download_icms() {
    local VERSION="${envs[VERSION]}"

    echo "Downloading InstantCMS v$VERSION..."
    git clone -q --branch $VERSION $ICMS_REPO || { 
        echo "Failed to download. Invalid version?"
        exit 1
    }

    echo "Cleaning repository stuff..."
    rm -rf $DIR/icms2/.git
    rm -rf $DIR/icms2/.github
    rm -rf $DIR/icms2/.gitignore
    rm -f $DIR/icms2/ISSUE_TEMPLATE.md
    rm -f $DIR/icms2/README.md
    rm -f $DIR/icms2/README_RU.md
    rm -f $DIR/icms2/LICENSE
}

checkout_icms() {
    local REPO_URL=$1
    echo "Downloading site from $REPO_URL..."
    rm -rf $DIR/icms2
    git clone -q $REPO_URL icms2 || { 
        echo "Failed to download. Invalid repository?"
        exit 1
    }
    echo "Checking downloaded contents..."
    if [ ! -f $DIR/icms2/system/core/core.php ]; then
        echo "InstantCMS not found in $DIR/icms2: Invalid repository?"
        exit 1
    fi   
    if [ -f $DIR/icms2/system/config/config.prod.php ]; then
        echo "Deploying production config..."
        mv $DIR/icms2/system/config/config.prod.php $DIR/icms2/system/config/config.php        
    fi   
    echo "Deploying database dumps..."
    rm -f $DIR/mysql/dump/*
    mv $DIR/icms2/*.sql $DIR/mysql/dump
}

set_icms_permissions() {
    echo "Setting up permissions..."
    find $DIR/icms2/ -type f -exec chmod 644 {} \;
    find $DIR/icms2/ -type d -exec chmod 755 {} \;
    chmod -R 777 $DIR/icms2/upload
    chmod -R 777 $DIR/icms2/cache
    chmod 777 $DIR/icms2/system/config
}

run_docker() {
    echo "Starting Docker..."
    docker-compose up -d
}

completed() {
    echo "Done!"
    echo " "
    exit 0
}

main() {

    if [[ $MODE == "install" ]]; then 
        run_wizard
        clear_installation
        save_config
        download_icms
        set_icms_permissions
        run_docker
        completed
    fi

    if [[ $MODE == "deploy" ]]; then 
        local REPO_URL=$2

        if [[ $REPO_URL == "" ]]; then
            run_wizard
            save_config
            set_icms_permissions
            run_docker
            completed
        fi

        if [[ $REPO_URL != "" ]]; then
            run_wizard
            save_config
            checkout_icms $REPO_URL
            set_icms_permissions
            run_docker
            completed
        fi
    fi

    if [[ $MODE == "clear" ]]; then 
        clear_installation
        rm -f $DIR/mysql/dump/*.sql
        completed
    fi

}

main $@
