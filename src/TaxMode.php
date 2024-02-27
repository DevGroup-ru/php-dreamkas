<?php

namespace StudServise\Dreamkas;


/**
 * Class TaxMode описывает возможные системы налогооблажения
 */
class TaxMode
{
    // ОСНО
    const MODE_DEFAULT = 'DEFAULT';
    // УСН доход
    const MODE_SIMPLE = 'SIMPLE';
    // УСН Доход-расход
    const MODE_SIMPLE_WO = 'SIMPLE_WO';
    // ЕНВД
    const MODE_ENVD = 'ENVD';
    // ЕСХН
    const MODE_AGRICULT = 'AGRICULT';
    // Патент
    const MODE_PATENT = 'PATENT';
}
