# kondicionieriuirengimas.lt

PHP svetainė: kondicionierių ir šilumos siurblių pardavimas, montavimas, remontas ir priežiūra visoje Lietuvoje.

## Diegimas
- PHP 8.0+ su cURL palaikymu.
- Vieši svetainės failai yra šiame repository šaknyje.
- `private/` (admin prisijungimai, Telegram token, GTM nustatymai) ir `uploads/` yra serverio duomenys – nekeliami į GitHub ir per diegimą neperrašomi.
- Automatinis FTP diegimas dar nesukonfigūruotas. **Prieš diegimą reikia patvirtinti hostingo katalogą ir FTP protokolą.**

## Duomenų saugumas
Neįkelkite į repository FTP slaptažodžių, Telegram API rakto ar `private/` katalogo. GitHub Actions duomenys turi būti tik repository Secrets.
