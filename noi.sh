#!/usr/bin/env bash -l

# Set NOINSTAGRAM_GITHUB_TOKEN environment variable somewhere
# In my case, it is set in ~/.bash_profile and gets loaded with the -l parameter above

#should change this to you
github_username=datvance

#maybe flimsy, could just hard-code it
project_dir="$( cd "$( dirname "$0" )" && pwd )"

php ${project_dir}/build.php;

changes=$(git status --short ${project_dir}/docs/index.html);

if [ -n "$changes" ]; then
	timestamp=$(date +"%Y-%m-%d_%H-%M-%S")
	git commit ${project_dir}/docs/index.html -m "Build ${timestamp}"
	git push https://${NOINSTAGRAM_GITHUB_TOKEN}:x-oauth-basic@github.com/${github_username}/noinstagram.git master
fi
