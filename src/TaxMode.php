<?php

namespace DevGroup\Dreamkas;


/**
 * Class TaxMode описывает возможные системы налогооблажения
 */
class TaxMode
{
    // ОСНО
    public const MODE_DEFAULT = 'DEFAULT';
    // УСН доход
    public const MODE_SIMPLE = 'SIMPLE';
    // УСН Доход-расход
    public const MODE_SIMPLE_WO = 'SIMPLE_WO';
    // ЕНВД
    public const MODE_ENVD = 'ENVD';
    // ЕСХН
    public const MODE_AGRICULT = 'AGRICULT';
    // Патент
    public const MODE_PATENT = 'PATENT';
}
