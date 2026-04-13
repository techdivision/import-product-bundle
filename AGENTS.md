# AGENTS.md - import-product-bundle

## Zweck & Verantwortung

Das `import-product-bundle` Modul bietet **Bundle Product Import-Funktionalität**. Es ist ein **Tier 5 Modul** und erweitert `import-product`.

**Hauptverantwortung:**
- Bundle Product Import
- Bundle Option Import
- Bundle Selection Import
- Repository Pattern für Bundle-Daten
- Service Layer für Bundle-Verarbeitung
- Observer Pattern für Bundle-Hooks

## Architektur & Design Patterns

### Kern-Klassen
- **BundleProductRepository**: Persistierung von Bundle Products
- **BundleOptionRepository**: Persistierung von Bundle Options
- **BundleSelectionRepository**: Persistierung von Bundle Selections
- **BundleObserver**: Observer für Hooks

### Verwendete Patterns
- **Observer Pattern**: Für Bundle-Hooks
- **Repository Pattern**: Für Daten-Persistierung
- **Service Layer**: Für Business Logic

## Abhängigkeiten

### Externe Pakete
- **Keine**

### TechDivision Dependencies
- **import-product** ^26.2 - Product Importer

### Abhängig von diesem Modul (1 Reverse Dependency)
- **import-product-bundle-ee** - EE Bundle Extensions

## Wichtige Entry Points

### Repository Klassen
```php
// Bundle Product Repository
BundleProductRepository::create($row): void

// Bundle Option Repository
BundleOptionRepository::create($row): void

// Bundle Selection Repository
BundleSelectionRepository::create($row): void
```

## Events & Extension Points

**Keine Events** - Tier 5 Importer-Modul

## Hints für KI-Agenten

### Wichtig zu verstehen
1. **Tier 5 Modul**: Erweitert Product Importer
2. **Bundle-fokussiert**: Spezialisiert auf Bundle Products
3. **Observer Pattern**: Für Hooks
4. **Repository Pattern**: Für Persistierung

## Bekannte Einschränkungen

- **Bundle-Only**: Keine anderen Product-Typen
- **Abhängig von Products**: Erfordert Products zu existieren

## Zusammenfassung

`import-product-bundle` ist ein **Tier 5 Modul**, das Bundle Product Import-Funktionalität bietet. Es erweitert den Product Importer mit spezialisierter Funktionalität für Bundle Products.

**Für Agenten:** Verstehe dieses Modul als **Bundle Product Importer** mit Observer und Repository Pattern.
