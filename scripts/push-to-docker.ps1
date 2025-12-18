# Build only the app image and push it to Docker Hub under your username `uweracarine`.
# You will be prompted to login if not already logged in.

# Build the app image using compose
docker compose build app

# Ensure local image exists (compose names image as specified in docker-compose.yml)
# Tag is already set in compose (image: uweracarine/best-app:latest) but tag explicitly to be safe
$localImage = "best-app:latest"
$remoteImage = "uweracarine/best-app:latest"

# If local image exists under a different name, retag it
if ((docker images -q $localImage) -ne "") {
    docker tag $localImage $remoteImage
}

# Login to Docker Hub (interactive)
docker login

# Push the image
docker push $remoteImage

Write-Host "Push complete: $remoteImage"