<h1>Šablona na basic web-app</h1>

<b>Instalace</b>
<ul>
  <li>Směrovat veškerý provoz na index.php ve veřejné složce 'www'</li>
  <li>Upravit přístupové údaje k databázi v config.neon a service.neon</li>
  <li>Naimportovat přiložený defaultní SQL soubor</li>
  <li>Složka 'temp' musí mít práva pro zápis</li>
  <li>Složka 'session' ve složce 'temp' musí mít práva pro zápis</li>
  <li>Složka 'Media' a její podružné složky ve složce 'app' musí mít práva pro zápis</li>
</ul>

<b>Defaultní administrátorský účet:</b>
admin/admin

<p>Aplikace je defaultně spuštěna v režimu údržby, tedy je přístupná pouze uživatelům s rolí admina. Po prvním přihlašení je doporučeno vytvořit nový administrátorský účet a odmazání defaultního admina.</p>
