# Katalóg e-kníh

Jednoduchá webová aplikácia pre správu katalógu e-kníh. Vyvinutá v čistom PHP 8 (OOP) bez frameworku, s MySQL databázou a Docker prostredím.

## Požiadavky
- Docker a Docker Compose
- Node.js a NPM (pre Webpack kompiláciu)

## Inštalácia a spustenie

1. **Klonovanie repozitára**
   ```bash
   git clone <url_repozitara>
   cd katalog_knih
   ```

2. **Inštalácia frontend závislostí**
   ```bash
   npm install
   npm run build
   ```

3. **Spustenie prostredia cez Docker**
   ```bash
   docker-compose up -d
   ```

Aplikácia následne pobeží na [http://localhost:8080](http://localhost:8080).
Databáza pobeží na porte `3307` a automaticky sa do nej importuje schéma z `database/schema.sql`.

## Prihlasovacie údaje (Predvolené)
*Bude doplnené neskôr*

## Import kníh
Pre automatický import môžete po prihlásení v administrácii nahrať dáta priamo z ukážkového súboru umiestneného v `database/seed/books.json`.
