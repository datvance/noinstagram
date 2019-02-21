<?php
require __DIR__ . '/vendor/autoload.php';

$items_per_account = 6;
$columns = 3;
$tz_offset = 6 * 3600;

$instagram = new \InstagramScraper\Instagram();

function is_tag($string)
{
  return strpos($string, '#') === 0;
}

function detagify($string)
{
  return str_replace('#', '', $string);
}

$items = [];
$accounts = json_decode(file_get_contents(__DIR__ . '/accounts.json'));
foreach($accounts as $account)
{
  try
  {
    if(is_tag($account))
    {
      $response = $instagram->getMediasByTag(detagify($account), $items_per_account);
    }
    else
    {
      $response = $instagram->getMedias($account, $items_per_account);
    }
  }
  catch(Exception $e)
  {
    echo $e->getCode() . ' ' . $e->getMessage();
    exit;
  }

  foreach($response as $media)
  {
    /** @var \InstagramScraper\Model\Media $media */
    $created_at = $media->getCreatedTime();
    while(isset($items[$created_at]))
    {
      $created_at += 1;
    }

    $media->account = $account;
    $items[$created_at] = $media;
  }
}

krsort($items);

$tile_template = file_get_contents(__DIR__ . '/templates/tile-card.html');

$rows = array_chunk($items, $columns);

$tiles = [];
foreach($rows as $row)
{
  $html = '';

  foreach($row as $item)
  {
    /** @var \InstagramScraper\Model\Media $item */

    $search = [
      '{{ @src }}',
      '{{ @account }}',
      '{{ @account_link }}',
      '{{ @num_likes}}',
      '{{ @num_comments }}',
      '{{ @caption }}',
      '{{ @created_time }}',
      '{{ @link }}',
    ];

    $caption = ($item->getType() == 'video' ? 'Video: ' : '') . $item->getCaption();

    $account_link = 'https://instagram.com/';
    $account_link .= is_tag($item->account) ? 'explore/tags/' . detagify($item->account) : $item->account;
    $account_link .= '/';

    $replace = [
      $item->getImageHighResolutionUrl(),
      $item->account,
      $account_link,
      $item->getLikesCount(),
      $item->getCommentsCount(),
      preg_replace('@#\S{1,}@', '', $caption),
      date('F j, Y g:i a', ($item->getCreatedTime() - $tz_offset)),
      $item->getLink(),
    ];

    $html .= str_replace($search, $replace, $tile_template);
  }

  $tiles[] = '<div class="tile is-ancestor">' . $html . '</div>';
}

file_put_contents(
  __DIR__ . '/docs/index.html',
  str_replace('{{ @tiles }}', join("\n", $tiles), file_get_contents(__DIR__ . '/templates/index.html'))
);