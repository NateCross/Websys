# For Nate only

http_location="/srv/http/webtech"

if [ ! -d "$http_location" ]; then
	echo "Symlinking backend to '/srv/http/'..."
	sudo ln -s "$PWD" "${http_location}"
fi

echo "Starting nginx..."
sudo systemctl start nginx
