#!/usr/bin/env bash -l

# Set NOINSTAGRAM_GITHUB_TOKEN environment variable somewhere
# In my case, it is set in ~/.bash_profile and gets loaded with the -l parameter above

#should change this to you
github_username=datvance

project_dir="$( cd "$( dirname "$0" )" && pwd )"

php ./build.php;

changes=$(git status --short ./docs/index.html);

if [ -n "$changes" ]; then
	timestamp=$(date +"%Y-%m-%d_%H-%M-%S")
	git commit ./docs/index.html -m "Build ${timestamp}"
	git push https://${NOINSTAGRAM_GITHUB_TOKEN}:x-oauth-basic@github.com/${github_username}/noinstagram.git master
fi
