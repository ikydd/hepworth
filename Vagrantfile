Vagrant.configure("2") do |config|

    config.vm.box = "scotch/box"
    config.vm.network "private_network", ip: "192.168.33.10"
    config.vm.hostname = "scotchbox"
    
    config.vm.provision :shell, path: "bootstrap.sh"
    
    config.vm.synced_folder ".", "/var/www", :mount_options => ["dmode=777", "fmode=666"]
    config.vm.synced_folder "../vhosts", "/etc/apache2/sites-enabled", :mount_options => ["dmode=777", "fmode=666"]
    config.vm.synced_folder "./.nginx", "/etc/nginx/sites-available", :mount_options => ["dmode=777", "fmode=666"]
    config.vm.synced_folder "./.db", "/var/lib/mysql", :mount_options => ["dmode=777", "fmode=666"]

end