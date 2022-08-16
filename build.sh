rm -rf dist
mkdir dist
zip -R ./dist/mash-wallet-plugin.zip 'assets/*' 'css/*' 'images/*' 'includes/*' 'js/*' 'LICENSE' 'readme.txt' 'mash.php'
echo 'Plugin Built'
