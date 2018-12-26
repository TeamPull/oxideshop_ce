
if [ ! -f selenium-server-standalone-2.47.1.jar ]; then
  wget -nv https://raw.githubusercontent.com/OXID-eSales/oxvm_assets/master/selenium-server-standalone-2.47.1.jar
fi
if [ ! -f firefox-mozilla-build_31.0-0ubuntu1_amd64.deb ]; then
  wget -nv https://raw.githubusercontent.com/OXID-eSales/oxvm_assets/master/firefox-mozilla-build_31.0-0ubuntu1_amd64.deb
fi

# replace configuration values in config.inc.php
sed -i 's|<dbHost>|localhost|; s|<dbName>|oxideshop|; s|<dbUser>|root|; s|<dbPwd>||; s|<sShopURL>|http://localhost|; s|<sShopDir>|'$TRAVIS_BUILD_DIR'/source|; s|<sCompileDir>|'$TRAVIS_BUILD_DIR'/source/tmp|; s|$this->iDebug = 0|$this->iDebug = 1|' source/config.inc.php
sed -i "s|\$this->edition = ''|\$this->edition = 'CE'|" source/config.inc.php


sudo dpkg -i firefox-mozilla-build_31.0-0ubuntu1_amd64.deb
#sudo apt-get install -f -y
ls -al ${TRAVIS_BUILD_DIR}/start_selenium.sh
ls -al /usr/bin/xvfb-run

xvfb-run --server-args="-screen 0, 1024x768x24" ${TRAVIS_BUILD_DIR}/start_selenium.sh
