<?php
namespace Maiorano\Shortcodes\Library;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;
use Maiorano\Shortcodes\Contracts\AttributeInterface;
use Maiorano\Shortcodes\Contracts\ShortcodeTrait;
use Maiorano\Shortcodes\Contracts\AttributeTrait;
use \DateTime;
use \DateInterval;

/**
 * Calculates the age of something
 * Usage: [age units=years]September 19th 1984[/age]
 * @package Maiorano\Shortcodes\Library
 */
class Age implements ShortcodeInterface, AttributeInterface
{
    use ShortcodeTrait, AttributeTrait;

    /**
     * @var string
     */
    protected $name = 'age';

    /**
     * @var array
     */
    protected $attributes = ['units' => 'years'];

    /**
     * @param string|null $content
     * @param array $atts
     * @return string
     */
    public function handle($content = null, array $atts = [])
    {
        if (!$content) {
            return '';
        }

        $now = new DateTime('now');
        $birthday = new DateTime($content);
        $v = $this->calculate($atts['units'], $now->diff($birthday));

        return sprintf('%d %s', $v, $atts['units']);
    }

    /**
     * @param $units
     * @param DateInterval $diff
     * @return mixed
     */
    private function calculate($units, DateInterval $diff)
    {
        $calculator = [
            'centuries' => function (DateInterval $diff) {
                return $diff->y / 100;
            },
            'decades' => function (DateInterval $diff) {
                return $diff->y / 10;
            },
            'years' => function (DateInterval $diff) {
                return $diff->y;
            },
            'months' => function (DateInterval $diff) {
                return $diff->y * 12 + $diff->m;
            },
            'days' => function (DateInterval $diff) {
                return $diff->days + $diff->d;
            },
            'hours' => function (DateInterval $diff) {
                return ($diff->days * 24) + $diff->h;
            },
            'minutes' => function (DateInterval $diff) {
                return ($diff->days * 24 * 60) + $diff->i;
            },
            'seconds' => function (DateInterval $diff) {
                return ($diff->days * 24 * 60 * 60) + $diff->s;
            }
        ];
        $u = isset($calculator[$units]) ? $units : 'years';

        return $calculator[$u]($diff);
    }
}
