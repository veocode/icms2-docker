#!/bin/bash
MODE=${1:-"help"}
if [[ $MODE == "help" || $MODE == "--help"  || $MODE == "-h" ]]; then
    echo -e ""
    echo -e "\e[96mInstantCMS 2 Official Docker Toolkit\e[39m"
    echo -e ""
    echo -e "      init.sh <COMMAND>"
    echo -e "      init.sh deploy [REPO_URL] [--skip-wizard] [--with-pma]"
    echo -e "      init.sh makecert <CERT_EMAIL> <CERT_DOMAIN> [--force-https]"
    echo -e ""
    echo -e "\e[96mAvailable Commands:\e[39m"
    echo -e ""
    echo -e "      \e[2mBefore installation:\e[22m "
    echo -e ""
    echo -e "          install        \e[2m- Run Installation Wizard to install InstanCMS 2 from scratch\e[22m"
    echo -e "          deploy         \e[2m- Deploy contents of icms2 folder (see Options and Flags below)\e[22m"
    echo -e ""
    echo -e "      \e[2mAfter installation:\e[22m "
    echo -e ""
    echo -e "          start          \e[2m- Start Docker containers\e[22m"
    echo -e "          stop           \e[2m- Stop Docker containers\e[22m"
    echo -e "          restart        \e[2m- Restart Docker containers\e[22m"
    echo -e "          shell          \e[2m- Connect to web-server shell\e[22m"
    echo -e "          force-https    \e[2m- Force redirect from HTTP to HTTPS\e[22m"
    echo -e ""
    echo -e "      \e[2mOther:\e[22m "
    echo -e ""
    echo -e "          help           \e[2m- Show this usage help\e[22m"
    echo -e "          clear          \e[2m- Reset current installation (stop containers first!)\e[22m"
    echo -e ""
    echo -e "\e[96mAvailable Options:\e[39m"
    echo -e ""
    echo -e "          REPO_URL       \e[2m- HTTPS URL of Git repository to use with deploy command\e[22m"
    echo -e ""
    echo -e "\e[96mAvailable Flags:\e[39m"
    echo -e ""
    echo -e "          --skip-wizard  \e[2m- Don't run Installation Wizard on deploy, use .env contents instead\e[22m"
    echo -e "          --with-pma     \e[2m- Force install phpMyAdmin (to use with --skip-wizard flag)\e[22m"
    echo -e "          --force-https  \e[2m- Force redirect from HTTP to HTTPS when installing SSL certificate\e[22m"
    echo -e ""
    echo -e "\e[96mExamples:\e[39m"
    echo -e ""
    echo -e "      \e[2mInstall InstantCMS 2 from scratch:\e[22m "
    echo -e "          \e[2m$\e[22m init.sh install "
    echo -e ""
    echo -e "      \e[2mDeploy InstantCMS 2 from Git repository:\e[22m "
    echo -e "          \e[2m$\e[22m init.sh deploy https://github.com/user/repo.git"
    echo -e ""
    echo -e "      \e[2mDeploy InstantCMS 2 from Git repository and phpMyAdmin using config from .env:\e[22m "
    echo -e "          \e[2m$\e[22m init.sh deploy https://github.com/user/repo.git --skip-wizard --with-pma"
    echo -e ""
    echo -e "\e[96mDocumentation:\e[39m"
    echo -e ""
    echo -e "      \e[2mCheck official repository:\e[22m "
    echo -e "          https://github.com/veocode/icms2-docker "
    echo -e ""
    exit 0
fi

ARGS=$@
ICMS_REPO="https://github.com/instantsoft/icms2.git"
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

FLAG_SKIP_WIZARD=0; if [[ $ARGS == *"--skip-wizard"* ]]; then FLAG_SKIP_WIZARD=1; fi
FLAG_WITH_PMA=0; if [[ $ARGS == *"--with-pma"* ]]; then FLAG_WITH_PMA=1; fi
FLAG_FORCE_HTTPS=0; if [[ $ARGS == *"--force-https"* ]]; then FLAG_FORCE_HTTPS=1; fi


declare -A envs
envs[VERSION]=2.14.2
envs[HTTP_HOST]=icms2-docker
envs[HTTP_PORT]=80
envs[MYSQL_DATABASE]=icmsdb
envs[MYSQL_USER]=icmsdb
envs[MYSQL_PASSWORD]=secret
envs[MYSQL_ROOT_PASSWORD]=rootsecret
envs[PHPMYADMIN_INSTALL]=y
envs[PHPMYADMIN_PORT]=8080

declare -A prompts
prompts[VERSION]="InstantCMS version to install"
prompts[HTTP_HOST]="Web-server Host"
prompts[HTTP_PORT]="Web-server Port"
prompts[MYSQL_DATABASE]="MySQL Database"
prompts[MYSQL_USER]="MySQL User"
prompts[MYSQL_PASSWORD]="MySQL User Password"
prompts[MYSQL_ROOT_PASSWORD]="MySQL Root Password"
prompts[PHPMYADMIN_INSTALL]="Install phpMyAdmin? (y/n)"
prompts[PHPMYADMIN_PORT]="phpMyAdmin Port"

order=(VERSION HTTP_HOST HTTP_PORT MYSQL_DATABASE MYSQL_USER MYSQL_PASSWORD MYSQL_ROOT_PASSWORD PHPMYADMIN_INSTALL PHPMYADMIN_PORT)

PHPMYADMIN_INSTALL=y

get_container_name() {
    echo $(basename $DIR)_icms_1
}

clear_installation() {
    echo "Cleaning installation..."
    rm -rf $DIR/icms2
    rm -rf $DIR/services/mysql/db
    rm -rf $DIR/services/apache/logs/*
    rm -rf $DIR/services/apache/letsencrypt/*
    rm -f $DIR/services/apache/letsencrypt/.* 2> /dev/null
    mkdir $DIR/icms2
    mkdir $DIR/services/mysql/db
    echo "" > $DIR/icms2/.gitkeep
    echo "" > $DIR/services/mysql/db/.gitkeep
    echo "" > $DIR/services/apache/logs/.gitkeep
    echo "" > $DIR/services/apache/letsencrypt/.gitkeep
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
    rm -rf $DIR/icms2
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
    echo "Downloading site from repository..."
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
    echo "Cleaning downloaded contents..."
    rm -f $DIR/icms2/*.txt
    rm -f $DIR/icms2/*.md
}

deploy_configs() {
    if [ -f $DIR/icms2/system/config/config.prod.php ]; then
        echo "Deploying production config..."
        mv $DIR/icms2/system/config/config.prod.php $DIR/icms2/system/config/config.php
    fi
    echo "Deploying database dumps..."
    rm -f $DIR/services/mysql/dump/*
    mv $DIR/icms2/*.sql $DIR/services/mysql/dump
}

set_icms_permissions() {
    echo "Setting up permissions..."
    find $DIR/icms2/ -type f -exec chmod 644 {} \;
    find $DIR/icms2/ -type d -exec chmod 755 {} \;
    chmod -R 777 $DIR/icms2/upload
    chmod -R 777 $DIR/icms2/cache
    chmod 777 $DIR/icms2/system/config
}

start_docker() {
    echo "Starting Docker containers..."
    docker-compose up -d
}

stop_docker() {
    echo "Stopping Docker containers..."
    docker-compose down
}

restart() {
    stop_docker
    start_docker
}

force_https() {
    echo "Enabling force redirect to HTTPS..."
    local HTACCESS="$DIR/icms2/.htaccess"
    local S
    local R
    S='# RewriteCond %{HTTPS} !=on'
    R='RewriteCond %{HTTPS} !=on'
    sed -i "s/$S/$R/g" $HTACCESS
    S='# RewriteRule \^(\.\*)\$ https:\/\/%{HTTP_HOST}\/\$1 \[R=301,L\]'
    R='RewriteRule \^(\.\*)\$ https:\/\/%{HTTP_HOST}\/\$1 \[R=301,L\]'
    sed -i "s/$S/$R/g" $HTACCESS
}

make_cert() {
    local EMAIL=$1
    local DOMAIN=$2
    if [[ $EMAIL == "" || $DOMAIN == "" ]]; then
        echo "USAGE: init.sh makecert <EMAIL> <DOMAIN>"
        exit 1
    fi
    echo "Creating certificate..."
    docker-compose exec -T icms certbot --apache --agree-tos -n -m $EMAIL -d $DOMAIN  || {
        echo "Failed to create certificate"
        exit 1
    }

    echo "Installing certificate..."
    cat $DIR/services/apache/conf/site-le-ssl.conf >> $DIR/services/apache/conf/site.conf
    rm -f $DIR/services/apache/conf/site-le-ssl.conf

    if [[ $FLAG_FORCE_HTTPS == 1 ]]; then
        force_https
    fi

    echo "Restarting web-server..."
    restart
}

header() {
    echo -e "\e[96micms2-docker: $MODE\e[39m"
}

completed() {
    echo -e "\e[96mDone!\e[39m"
    echo ""
    exit 0
}

main() {

    if [[ $MODE == "install" ]]; then
        run_wizard
        clear_installation
        save_config
        download_icms
        set_icms_permissions
        start_docker
        completed
    fi

    if [[ $MODE == "deploy" ]]; then
        local REPO_URL=$2

        if [[ $REPO_URL == "" ]]; then
            run_wizard
            save_config
            deploy_configs
            set_icms_permissions
            start_docker
            completed
        fi

        if [[ $REPO_URL != "" ]]; then
            if [[ $FLAG_SKIP_WIZARD != 1 ]]; then
                run_wizard
                save_config
            else
                header
            fi
            if [[ $FLAG_WITH_PMA == 1 ]]; then
                cat $DIR/vendor/phpmyadmin.yml >> $DIR/docker-compose.yml
            fi
            checkout_icms $REPO_URL
            deploy_configs
            set_icms_permissions
            start_docker
            completed
        fi
    fi

    if [[ $MODE == "force-https" ]]; then
        header
        force_https
        restart
        completed
    fi

    if [[ $MODE == "clear" ]]; then
        header
        clear_installation
        rm -f $DIR/services/mysql/dump/*.sql
        completed
    fi

    if [[ $MODE == "restart" ]]; then
        header
        restart
        completed
    fi

    if [[ $MODE == "start" ]]; then
        header
        start_docker
        completed
    fi

    if [[ $MODE == "stop" ]]; then
        header
        stop_docker
        completed
    fi

    if [[ $MODE == "shell" ]]; then
        header
        docker-compose exec icms bash
        completed
    fi

    if [[ $MODE == "makecert" ]]; then
        header
        make_cert $2 $3
        completed
    fi

    if [[ $MODE == "printfile" ]]; then
        local FILE=$2
        if [[ $FILE == "" ]]; then
            echo "USAGE: init.sh printfile <FILE>"
            echo "EXAMPLE: init.sh printfile <FILE>"
            exit 1
        fi
        header
        docker-compose exec icms bash
        completed
    fi

    echo "Unknown command: $MODE"
    echo "See ./init.sh --help"
    echo ""
    exit 1

}

main $@
