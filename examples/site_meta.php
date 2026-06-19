<?php

/**
 * SiteMeta - 站点元信息管理
 * 用于存储和生成站点的元数据描述
 */
class SiteMeta
{
    /**
     * @var array 站点配置
     */
    private array $config = [
        'site_name' => '爱游戏',
        'site_url' => 'https://ayx-chinese.com',
        'description' => '提供丰富的游戏资讯和评测内容',
        'keywords' => ['爱游戏', '游戏资讯', '游戏评测'],
        'language' => 'zh-CN',
        'author' => 'AYX Team'
    ];

    /**
     * @var array 附加元数据
     */
    private array $metaData = [];

    /**
     * 构造函数
     *
     * @param array $customConfig 自定义配置（可选）
     */
    public function __construct(array $customConfig = [])
    {
        if (!empty($customConfig)) {
            $this->config = array_merge($this->config, $customConfig);
        }
    }

    /**
     * 设置元数据
     *
     * @param string $key 键名
     * @param mixed $value 值
     * @return void
     */
    public function setMetaData(string $key, $value): void
    {
        $this->metaData[$key] = $value;
    }

    /**
     * 获取配置项
     *
     * @param string $key 配置键名
     * @return mixed|null 配置值或null
     */
    public function getConfig(string $key)
    {
        return $this->config[$key] ?? null;
    }

    /**
     * 生成简短描述文本
     *
     * @param int $maxLength 最大长度（默认200）
     * @param bool $includeKeywords 是否包含关键词
     * @return string 描述文本
     */
    public function generateShortDescription(int $maxLength = 200, bool $includeKeywords = true): string
    {
        $description = $this->config['description'] ?? '';

        if ($includeKeywords && !empty($this->config['keywords'])) {
            $keywordsStr = implode('，', $this->config['keywords']);
            $description .= ' 涵盖' . $keywordsStr . '等内容。';
        }

        if (!empty($this->metaData)) {
            foreach ($this->metaData as $key => $value) {
                if (is_string($value)) {
                    $description .= ' ' . $value;
                }
            }
        }

        if (mb_strlen($description) > $maxLength) {
            $description = mb_substr($description, 0, $maxLength - 3) . '...';
        }

        return $description;
    }

    /**
     * 获取站点完整信息数组
     *
     * @return array 包含站点信息的数组
     */
    public function getSiteInfo(): array
    {
        return [
            'name' => $this->config['site_name'],
            'url' => $this->config['site_url'],
            'language' => $this->config['language'],
            'author' => $this->config['author'],
            'description' => $this->generateShortDescription(),
            'keywords' => $this->config['keywords']
        ];
    }

    /**
     * 输出HTML友好的meta标签（示例）
     *
     * @return string HTML字符串
     */
    public function renderMetaTags(): string
    {
        $name = htmlspecialchars($this->config['site_name'], ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($this->generateShortDescription(), ENT_QUOTES, 'UTF-8');
        $keywords = htmlspecialchars(implode(', ', $this->config['keywords']), ENT_QUOTES, 'UTF-8');

        return <<<HTML
<meta name="description" content="{$desc}" />
<meta name="keywords" content="{$keywords}" />
<meta property="og:site_name" content="{$name}" />
HTML;
    }
}

// 示例用法
$meta = new SiteMeta();
$meta->setMetaData('custom_tag', '最新游戏动态');

echo "=== 站点信息 ===\n";
print_r($meta->getSiteInfo());

echo "\n=== 简短描述 ===\n";
echo $meta->generateShortDescription(150) . "\n";

echo "\n=== Meta标签 ===\n";
echo $meta->renderMetaTags() . "\n";