# TODO - Coloring Grid Generator

## Zabezpieczenia prawne i regulaminowe

### 1. Regulamin / Terms of Service
- [ ] Dodać stronę z regulaminem (`/terms`)
- [ ] Klauzula o prawach autorskich:
  ```
  Użytkownik oświadcza, że posiada prawa do przesyłanych obrazów
  lub korzysta z nich zgodnie z prawem. Właściciel serwisu nie ponosi
  odpowiedzialności za naruszenia praw autorskich przez użytkowników.
  ```
- [ ] Informacja o użytku osobistym/edukacyjnym
- [ ] Link do regulaminu w stopce aplikacji

### 2. Dodatkowe zabezpieczenia przy uploaderze
- [ ] Checkbox przed wysłaniem formularza:
  - "Potwierdzam, że posiadam prawa do tego obrazu lub używam go zgodnie z prawem"
  - Wymagany do zaznaczenia przed generowaniem siatki
- [ ] Info pod polem upload: "Tylko do użytku osobistego/edukacyjnego"

### 3. Zarządzanie danymi
- [ ] Rozważyć automatyczne usuwanie starych siatek (np. po 30 dniach)
- [ ] Dodać opcję "usuń po wygenerowaniu" - nie zapisuj w bazie, tylko pokaż i pozwól wydrukować
- [ ] Opcjonalnie: Dodać system kont z prywatnymi/publicznymi siatkami

### 4. Polityka prywatności
- [ ] Dodać stronę Privacy Policy (`/privacy`)
- [ ] Informacja jakie dane są przechowywane:
  - Oryginalna nazwa pliku
  - Ścieżka do pliku (przechowywana tymczasowo)
  - Dane siatki (grid_data, color_palette)
  - Brak przechowywania oryginalnych obrazów po przetworzeniu
- [ ] RODO compliance (jeśli UE):
  - Prawo do usunięcia danych
  - Informacja o celu przetwarzania

### 5. Disclaimer na stronie głównej
- [ ] Dodać krótki disclaimer na stronie głównej:
  ```
  To narzędzie służy do tworzenia siatek do kolorowania
  wyłącznie do użytku osobistego i edukacyjnego.
  ```

### 6. DMCA / Zgłaszanie naruszeń (jeśli publiczne)
- [ ] Dodać stronę z procedurą zgłaszania naruszeń (`/dmca`)
- [ ] Email kontaktowy do zgłoszeń
- [ ] Procedura usuwania zgłoszonych treści

### 7. Techniczne usprawnienia bezpieczeństwa
- [ ] Rozważyć usuwanie oryginalnych plików z `storage/app/public/originals` po przetworzeniu
- [ ] Dodać cronjob do czyszczenia starych plików
- [ ] Opcjonalnie: Watermark na podglądzie "Tylko do użytku prywatnego"

---

## Inne TODO (funkcjonalności)

### Usprawnienia UX
- [ ] Możliwość edycji istniejącej siatki (zmiana liczby kolorów, rozmiaru)
- [ ] Export do PDF (zamiast tylko print)
- [ ] Możliwość zapisania/pobrania jako PNG
- [ ] Preview obrazka przed wygenerowaniem siatki

### Optymalizacje
- [ ] Queue/Jobs dla przetwarzania dużych obrazków
- [ ] Progress bar podczas generowania
- [ ] Walidacja max rozmiaru obrazka (obecnie 10MB)

---

## Notatki prawne

**Aktualna sytuacja:**
- ✅ Nie przechowujemy długoterminowo oryginalnych plików
- ✅ Narzędzie do transformacji (jak Paint/Photoshop)
- ⚠️ Przechowujemy dane siatki w bazie (grid_data, color_palette)
- ⚠️ Brak regulaminu i disclaimera

**Ryzyko:**
- NISKIE jeśli: non-profit, hobby, użytek prywatny
- ŚREDNIE jeśli: publiczne, bez disclaimera, galeria siatek
- WYSOKIE jeśli: komercyjne, zarabianie na cudzych grafikach

**Zalecenia:**
1. Dodać prosty regulamin + disclaimer (minimum)
2. Checkbox potwierdzający prawa do obrazka
3. Rozważyć auto-usuwanie starych siatek
4. Jeśli komercyjne - konsultacja z prawnikiem
