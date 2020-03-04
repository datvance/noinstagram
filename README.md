# noinstagram

I got rid of my IG account some time ago, but I'd still like to see [photos from my wife](https://instagram.com/juiskie/) and [our dog](https://instagram.com/scooterpablovance/). I was just visiting the IG website for each public user I wanted to view, but that was way too much work. So I [built this](https://datvance.github.io/noinstagram/) one Sunday.

## Want your own noinstagram page?

- fork this repo
- set up [github pages](https://pages.github.com) for the forked repo and point it at /docs
- edit accounts.json with your preferred IG accounts and/or hashtags
- that's it. A GitHub Action should run every 6 hours updating your page. (You can change this in .github/workflows/build.yml)

You can also build using the noish.sh script. Just edit that script and set your GitHub username, and set
an environment variable with your GitHub access token.  

### also...

- thanks to [instagram-php-scraper](https://github.com/postaddictme/instagram-php-scraper) for the hard work
- style and features are pretty basic, but hey, pull requests welcome
- there are some variables in build.php you might want to change
