<p align="center"><a href="https://naykel.com.au" target="_blank"><img src="https://avatars0.githubusercontent.com/u/32632005?s=460&u=d1df6f6e0bf29668f8a4845271e9be8c9b96ed83&v=4" width="120"></a></p>

<a href="https://packagist.org/packages/naykel/gotime"><img src="https://img.shields.io/packagist/dt/naykel/gotime" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/naykel/gotime"><img src="https://img.shields.io/packagist/v/naykel/gotime" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/naykel/gotime"><img src="https://img.shields.io/packagist/l/naykel/gotime" alt="License"></a>

# NAYKEL Gotime

Starter package for NayKel Laravel applications.

## Adding New Icons

1. update extension `svg` to `blade.php`
```bash
find ./resources/views/components/icon -name "*.svg" -type f -exec bash -c 'mv -- "$0" "${0%.svg}.blade.php"' {} \;
```

1. add `$attributes`, `width` and `height`
```bash
# DON'T RUN THIS WITH EXPORTED FIGMA ICONS, IT WILL ADD THE ATTRIBUTES TWICE
find ./resources/views/components/icon -type f -name "*.blade.php" -exec sed -i 's/<svg xmlns="http:\/\/www\.w3\.org\/2000\/svg"/<svg {{ $attributes }} xmlns="http:\/\/www\.w3\.org\/2000\/svg" width="24" height="24"/g' {} +
# FOR FIGMA ICONS
find ./resources/views/components/icon/other -type f -name "*.blade.php" -exec sed -i 's/<svg xmlns="http:\/\/www\.w3\.org\/2000\/svg"/<svg {{ $attributes }} xmlns="http:\/\/www\.w3\.org\/2000\/svg"/g' {} +
```
