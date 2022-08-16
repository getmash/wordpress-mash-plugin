rm -rf dist
mkdir dist
zip -R ./dist/mash-wallet-plugin.zip 'assets/*' 'css/*' 'images/*' 'includes/*' 'js/*' 'LICENSE' 'readme.txt' 'mash.php' 'uninstall.php'
echo 'Plugin Built'
