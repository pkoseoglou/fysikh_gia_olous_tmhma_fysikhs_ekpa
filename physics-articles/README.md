# Φυσική για όλους - Οδηγίες MAMP

## Εγκατάσταση
1. Αποσυμπιέστε τον φάκελο `physics-articles` μέσα στο `Applications/MAMP/htdocs/`.
2. Ανοίξτε το MAMP και πατήστε **Start Servers**.
3. Ανοίξτε: `http://localhost:8888/physics-articles/`
4. Διαχείριση: `http://localhost:8888/physics-articles/admin.php`

## Αρχική σύνδεση
- Χρήστης: `admin`
- Κωδικός: `change-me-now`

Αλλάξτε αμέσως τα `ADMIN_USER` και `ADMIN_PASS` στο `config.php`.

## Περιεχόμενο
Τα άρθρα αποθηκεύονται στο `data/articles.json` και οι εικόνες στο `uploads/`. Δεν απαιτείται MySQL. Για να μπορεί η PHP να γράφει στους φακέλους, αν εμφανιστεί σφάλμα δικαιωμάτων στο macOS, εκτελέστε από Terminal:
`chmod -R 775 /Applications/MAMP/htdocs/physics-articles/data /Applications/MAMP/htdocs/physics-articles/uploads`

## Παραγωγική χρήση
Η έκδοση είναι ιδανική για τοπική λειτουργία και πρωτότυπο. Πριν δημοσιευτεί στο Internet χρειάζονται HTTPS, ισχυρός κωδικός, CSRF προστασία, backup και πιο ολοκληρωμένος έλεγχος χρηστών.
