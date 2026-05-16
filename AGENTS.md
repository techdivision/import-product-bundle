# AGENTS.md - import-product-bundle

## Zweck & Verantwortung

Das `import-product-bundle` Modul bietet **Bundle Product Import-Funktionalität** für komplexe Produktbündel. Es ist ein **Tier 5 Modul** in der Import-Hierarchie und erweitert das `import-product` Modul mit spezialisierter Business Logic für Bundle Products.

**Hauptverantwortung:**
- Bundle Product Import und Initialisierung
- Bundle Option Import (definiert wählbare Optionen)
- Bundle Selection Import (verknüpft einfache Produkte mit Optionen)
- Repository Pattern für Persistierung
- Service Layer für Bundle-spezifische Verarbeitung
- Observer Pattern Integration für Import-Pipeline Hooks

**Modul-Kategorie:** Integration/Extension Module  
**Komplexität:** ⭐⭐⭐⭐ (Hoch - komplexe Datenstrukturen)

## Architektur & Design Patterns

### Kern-Klassen
- **BundleProductRepository**: Persistiert Bundle Product Metadaten (Title, Description)
- **BundleOptionRepository**: Verwaltet Bundle Options (z.B. "Farbe", "Größe")
- **BundleSelectionRepository**: Verknüpft Simple Products mit Bundle Options
- **BundleProcessor**: Service Layer für Bundle-Verarbeitung
- **BundleObserver**: Observer für Lifecycle Hooks

### Verwendete Patterns
- **Observer Pattern**: Integration in Import-Pipeline Events
- **Repository Pattern**: Daten-Persistierungs-Schicht
- **Service Layer Pattern**: Geschäftslogik Isolation
- **Factory Pattern**: Object-Erstellung und Instantiation
- **Chain of Responsibility**: Verarbeitung in sequenziellen Schritten

### Datenfluss
```
Bundle CSV
    ↓
Parser (import-serializer)
    ↓
Converter (import-converter)
    ↓
Bundle Processor
    ├─→ BundleProductRepository (Bundle Basis)
    ├─→ BundleOptionRepository (Optionen definieren)
    └─→ BundleSelectionRepository (Produkte verknüpfen)
    ↓
Magento Database (catalog_bundle_*)
```

## Abhängigkeiten

### Externe Pakete
- **Keine direkten PHP-Pakete**

### TechDivision Dependencies
- **import-product** ^26.2 - Base Product Importer (Parent)
- **import-converter** - Data Conversion Framework
- **import-product-link** - Product Link Importer (für Relationen)

### Abhängig von diesem Modul (1 Reverse Dependency)
- **import-product-bundle-ee** - EE-spezifische Bundle-Extensions

## Wichtige Entry Points

### Repository Klassen
```php
// Bundle Product Repository - Basis Bundle-Daten
BundleProductRepository::create($row): void
BundleProductRepository::findByProductId($productId): BundleProduct

// Bundle Option Repository - Optionen für Bundle
BundleOptionRepository::create($row): void
BundleOptionRepository::findByBundleProductId($bundleId): array

// Bundle Selection Repository - Produkte in Optionen
BundleSelectionRepository::create($row): void
BundleSelectionRepository::findByBundleOptionId($optionId): array
```

### Service Methods
- `BundleProcessor::process()` - Haupteingangspunkt
- `BundleProcessor::validate()` - Pre-Import Validierung
- `BundleProcessor::persist()` - Persistierung aller 3 Tabellen

## Events & Extension Points

**Keine Custom Events** - Nutzt Parent Events aus import-product

### Observer Hooks
- `product.import.bundle.validate.pre` - Vor Validierung
- `product.import.bundle.option.process.post` - Nach Option-Verarbeitung
- `product.import.bundle.selection.process.post` - Nach Selection-Verarbeitung
- `product.import.bundle.persist.error` - Bei Fehler

## Database Schema

### Relevante Tabellen
- **catalog_bundle_product** - Bundle Basis-Informationen
  - `product_id` - Link zu catalog_product_entity
  - `sku_type` - 0 = dynamic, 1 = fixed
  - `weight_type` - 0 = dynamic, 1 = fixed

- **catalog_bundle_option** - Optionen innerhalb Bundle
  - `parent_id` - Link zu catalog_bundle_product
  - `title` - Option Name (z.B. "Geschenk-Optionen")
  - `type` - select, radio, checkbox, multi

- **catalog_bundle_selection** - Produkte in Optionen
  - `option_id` - Link zu catalog_bundle_option
  - `product_id` - Link zu catalog_product_entity
  - `selection_qty` - Menge des Produkts
  - `selection_price_type` - 0 = fixed, 1 = percent

## Common Use Cases

### Use Case 1: Einfaches Bundle
```php
// CSV Struktur:
// sku,bundle_option_title,bundle_option_type,bundle_selection_sku,bundle_qty

// BUNDLE-001,Geschenk,radio,GIFT-001,1
// BUNDLE-001,Geschenk,radio,GIFT-002,1
// BUNDLE-001,Versand,checkbox,SHIP-FAST,1

// Importer erstellt:
// 1. Bundle Product (BUNDLE-001)
// 2. Option "Geschenk" (type: radio)
// 3. Option "Versand" (type: checkbox)
// 4. Selections (Produkte in Optionen)
```

### Use Case 2: Custom Bundle-Logik
```php
class CustomBundleProcessor {
    public function processBundle($bundleData) {
        // Hook nach Bundle-Verarbeitung
        $this->eventManager->dispatch('custom.bundle.process', [
            'bundle_product' => $bundleData,
            'options' => $bundleData['options']
        ]);
    }
}
```

## Performance Considerations

### Wichtige Performance-Aspekte
1. **3-teilige Persistierung**: Schreib Bundle-Daten in 3 separate Tabellen
2. **Option/Selection Lookups**: Product IDs werden bei Selection-Verarbeitung nachgeschlagen
3. **Batch Processing**: Nutze Batch-Inserts für Options und Selections
4. **Transaktionen**: Nutze Database Transactions zur Datenkonsistenz

### Optimierungen
- Pre-load alle Product IDs um Lookups zu reduzieren
- Verwende Batch-Inserts für Options/Selections (max 1000 pro Batch)
- Cache Bundle-IDs während Verarbeitung
- Nutze Datenbank-Indizes auf parent_id und product_id

### Speicher-Optimierung
- Streame große Bundle-Dateien um Out-of-Memory zu vermeiden
- Verarbeite Bundles in Chunks (100-500 pro Iteration)

## Hints für KI-Agenten

### Kritisches Verständnis
1. **Tier 5 Modul**: Spezialisierte Extension des Product Importers
2. **3-Schichten Struktur**: Bundle → Options → Selections
3. **Observer Pattern**: Integration durch Lifecycle Hooks
4. **Repository Pattern**: Keine direkten SQL-Queries
5. **CSV-basiert**: Hierarchische Datenstruktur im Import

### Häufige Fehler
- ❌ Reihenfolge ignorieren (Bundle vor Options/Selections)
- ❌ Parent Product nicht validieren
- ❌ Selection-Produkte nicht prüfen
- ❌ Direkte SQL statt Repository-Pattern
- ❌ Bundle-Typen (dynamic vs fixed) nicht beachten

### Best Practices
- ✅ Validiere alle Products VOR Import
- ✅ Nutze Repository für alle Operationen
- ✅ Beachte SKU-Typen (fixed = summed price)
- ✅ Teste mit echten mehrstufigen CSV-Dateien
- ✅ Implementiere Custom Processor für komplexe Logik

## Known Limitations

- **Product-Type spezifisch**: Nur Bundle Products (type_id = bundle)
- **Hierarchisch**: Bundles müssen existieren bevor Options
- **Selection Limite**: Magento hat Grenzen bei Selections pro Option
- **Preis-Optionen**: Nur fixed oder percent, nicht absolut pro Selection
- **Keine Tier-Preise**: Bundle Pricing ist eigene Logik

## Related Modules

### Direct Dependencies
- **import-product** - Base Product Importer (Parent)
- **import-product-link** - Product Relations

### Related/Companion Modules
- **import-product-bundle-ee** - EE-spezifische Bundle-Features
- **import-product-grouped** - Grouped Product Importer (Alternative Grouping)
- **import-product-variant** - Configurable Product Importer

## Troubleshooting

### Problem: Bundles werden nicht importiert
**Mögliche Ursachen:**
1. Parent Product existiert nicht
2. Reihenfolge falsch (Options vor Bundle)
3. Produkt-Type nicht auf "bundle" gesetzt

**Lösung:**
- Validiere dass Basis-Produkt existiert
- Stelle sicher dass Bundle zuerst verarbeitet wird
- Prüfe dass type_id = 'bundle' ist

### Problem: Selections werden nicht importiert
**Mögliche Ursachen:**
1. Option-IDs nicht gültig
2. Selection-Produkte existieren nicht
3. CSV-Format falsch

**Lösung:**
- Validiere dass Optionen erstellt wurden
- Prüfe dass alle Selection-Produkte existieren
- Verwende korrektes CSV-Format

### Problem: Preis wird nicht berechnet
**Mögliche Ursachen:**
1. Bundle sku_type nicht auf "fixed" gesetzt
2. Selection-Preise nicht konfiguriert

**Lösung:**
- Nutze sku_type = fixed für feste Preise
- Konfiguriere selection_price_type korrekt

## Zusammenfassung

`import-product-bundle` ist ein **Tier 5 Importer-Modul**, das spezialisierte Bundle Product Import-Funktionalität mit komplexer Datenstruktur bereitstellt. Es verwaltet die Dreiteiligkeit: Bundle-Basisprodukte, Optionen und Selections in koordinierter Persistierung.

**Für KI-Agenten:** Verstehe dieses Modul als:
- **Bundle Product Importer** mit 3-Schichten-Architektur
- **Tier 5 Integration** mit sequenzieller Verarbeitung
- **CSV-basiert** mit hierarchischen Datenstrukturen
- **Komplexe Validierung** mit Multi-Table Konsistenz
