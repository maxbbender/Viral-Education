# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

	config.vm.box = "ubuntu/trusty64"
	#config.vm.network "forwarded_port", guest: 80, host: 8080
	config.ssh.username = "vagrant"
	config.ssh.password = "vagrant"
  
	# Vagrant public network setup
	config.vm.network "public_network"
	config.vm.network "public_network", ip: "192.168.1.170"

	# Lamp Config
	config.vm.define "lamp" do|lamp|
	  lamp.vm.hostname = "lamp" 
	  lamp.vm.provision :shell, path: "cfg_vagrant/script.sh" # This will install all the stuff you need
	end 
end
