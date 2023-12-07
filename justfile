svn_dir := "~/svn"
repo_name := "mash-monetize-earn-and-grow-your-experiences-w-bitcoin-lightning"

@help:
  just --list --justfile {{justfile()}}

# copy code over to svn repository
@sync-svn:
  cp ./readme.txt {{svn_dir}}/{{repo_name}}/trunk
  cp ./mash.php {{svn_dir}}/{{repo_name}}/trunk
  cp ./uninstall.php {{svn_dir}}/{{repo_name}}/trunk
  rm -rf {{svn_dir}}/{{repo_name}}/assets && cp -R ./assets/ {{svn_dir}}/{{repo_name}}/assets
  rm -rf {{svn_dir}}/{{repo_name}}/trunk/js && cp -R ./js/ {{svn_dir}}/{{repo_name}}/trunk/js
  rm -rf {{svn_dir}}/{{repo_name}}/trunk/includes && cp -R ./includes/ {{svn_dir}}/{{repo_name}}/trunk/includes
  rm -rf {{svn_dir}}/{{repo_name}}/trunk/images && cp -R ./images/ {{svn_dir}}/{{repo_name}}/trunk/images
  rm -rf {{svn_dir}}/{{repo_name}}/trunk/css && cp -R ./css/ {{svn_dir}}/{{repo_name}}/trunk/css
  
version := ""
# build plugin zip file and create release
@build:
  mkdir ./releases/v{{version}}
  zip -R ./releases/v{{version}}/mash-wordpress-plugin-{{version}}.zip "assets/*" "css/*" "images/*" "includes/*" "js/*" "LICENSE" "readme.txt" "mash.php" "uninstall.php" -x "node_modules/*" -x ".DS_Store"



