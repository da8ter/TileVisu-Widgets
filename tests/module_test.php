<?php

declare(strict_types=1);

/**
 * TileVisu Widgets: Abos und Referenzen nur fuer zugeordnete Variablen.
 *
 * RegisterMessage mit Absender 0 bedeutet „jedes Objekt": nicht zugeordnete Eigenschaften (0)
 * liessen MessageSink bei jeder Variablenaktualisierung im System laufen.
 *
 *   php tests/module_test.php
 * (SDK-Attrappe aus TileVisu-Raum-Titel-Kachel/tests/stubs, Pfad per SYMCON_STUBS aenderbar)
 */

$stubs = getenv('SYMCON_STUBS') ?: __DIR__ . '/../../TileVisu-Raum-Titel-Kachel/tests/stubs';
if (!is_file($stubs . '/autoload.php')) {
    fwrite(STDERR, "Symcon-Stubs nicht gefunden unter $stubs — Pfad über SYMCON_STUBS setzen.\n");
    exit(2);
}
require_once $stubs . '/autoload.php';
IPS\Kernel::reset();
require_once getenv('WIDGET_MODULE') ?: __DIR__ . '/../Widget/module.php';

// Die Attrappe merkt sich Abos und Referenzen nicht: die Probe zeichnet sie auf
class WidgetProbe extends TileVisuWidgetTile
{
    public array $abos = [];
    public array $referenzen = [];
    protected function RegisterMessage($SenderID, $Message)
    {
        $this->abos[] = [$SenderID, $Message];
    }
    protected function RegisterReference($ID)
    {
        $this->referenzen[] = $ID;
    }
    protected function UpdateVisualizationValue($Value)
    {
    }
}

$fehler = 0;
function check(bool $ok, string $label): void
{
    global $fehler;
    $fehler += $ok ? 0 : 1;
    echo ($ok ? 'OK   ' : 'FEHL ') . $label . "\n";
}

$var = IPS_CreateVariable(0);
IPS_SetName($var, 'Licht');
$id = IPS\ObjectManager::registerObject(1 /* Instance */);
ob_start();
IPS\InstanceManager::createInstance($id, [
    'ModuleID'   => '{8EBE9939-DC45-AFDA-571B-EC617A17FFCE}',
    'ModuleName' => 'WidgetProbe',
    'ModuleType' => 3,
    'Class'      => 'WidgetProbe',
]);
$m = IPS\InstanceManager::getInstanceInterface($id);
$m->SetProperty('Schalter1', $var);
$m->ApplyChanges();
ob_end_clean();

$absender = array_column(array_filter($m->abos, static fn (array $a): bool => $a[1] === VM_UPDATE), 0);
check(!in_array(0, $absender, true), 'No VM_UPDATE for sender 0 (unassigned properties)');
check($absender === [$var], 'VM_UPDATE only for the assigned variable');
check($m->referenzen === [$var], 'References only for the assigned variable, none for 0');

echo $fehler === 0 ? "\nAlle Prüfungen bestanden.\n" : "\n$fehler Prüfung(en) fehlgeschlagen.\n";
exit($fehler === 0 ? 0 : 1);
