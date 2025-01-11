package main

import (
	"bufio"
	"encoding/json"
	"fmt"
	"os"
	"time"
)

// Bu program basit bir kullanici giris sistemi olusturur.
// Go dilini yeni ogrenirken yazdigim ilk projelerden biri.
// Amac: Admin ve musteri kullanicilari icin farkli yetkiler tanimlamak
// ve tum islemleri log dosyasinda tutmak.

// Kullanici yapisini tanimliyoruz
// Bu yapi hem admin hem de musteriler icin kullanilacak
type Kullanici struct {
	KullaniciAdi string `json:"kullaniciAdi"` // JSON'da nasil saklanacagini belirtiyoruz
	Sifre        string `json:"sifre"`        // JSON'da nasil saklanacagini belirtiyoruz
	Tip          int    `json:"tip"`          // 0: Admin, 1: Musteri
}

// Global degiskenlerimiz
var kullaniciListesi []Kullanici // Tum kullanicilari tutacak dizi
var logDosyasi *os.File          // Log kayitlari icin dosya

// Kullanicilari JSON dosyasina kaydeden fonksiyon
func kullanicilariKaydet() error {
	dosya, hata := os.Create("kullanicilar.json")
	if hata != nil {
		return hata
	}
	defer dosya.Close() // Fonksiyon bitince dosyayi kapatmayi unutma

	jsonYazici := json.NewEncoder(dosya)
	return jsonYazici.Encode(kullaniciListesi)
}

// Kullanicilari JSON dosyasindan yukleyen fonksiyon  
func kullanicilariYukle() error {
	dosya, hata := os.Open("kullanicilar.json")
	if hata != nil {
		// Eger dosya yoksa, varsayilan kullanicilari olustur
		if os.IsNotExist(hata) {
			// Admin kullanicisi
			kullaniciListesi = append(kullaniciListesi, Kullanici{
				KullaniciAdi: "admin",
				Sifre:        "admin123",
				Tip:          0,
			})
			// Ornek musteri kullanicisi
			kullaniciListesi = append(kullaniciListesi, Kullanici{
				KullaniciAdi: "afra",
				Sifre:        "donas",
				Tip:          1,
			})
			return kullanicilariKaydet()
		}
		return hata
	}
	defer dosya.Close()

	jsonOkuyucu := json.NewDecoder(dosya)
	return jsonOkuyucu.Decode(&kullaniciListesi)
}

// Program basladiginda calisacak ilk fonksiyon
func init() {
	// Once kullanicilari yukle
	hata := kullanicilariYukle()
	if hata != nil {
		fmt.Println("Hata: Kullanicilar yuklenemedi ->", hata)
		os.Exit(1)
	}

	// Log dosyasini ac (yoksa olustur, varsa sonuna ekleme yap)
	logDosyasi, hata = os.OpenFile("log.txt", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
	if hata != nil {
		fmt.Println("Hata: Log dosyasi acilamadi ->", hata)
		os.Exit(1)
	}
}

// Log dosyasina mesaj yazan fonksiyon
func logYaz(mesaj string) {
	// Simdiki zamani al
	zamanDamgasi := time.Now().Format("2006-01-02 15:04:05")
	// Log mesajini olustur
	logMesaji := fmt.Sprintf("[%s] %s\n", zamanDamgasi, mesaj)
	// Dosyaya yaz
	yazmaHatasi, hata := logDosyasi.WriteString(logMesaji)
	if hata != nil || yazmaHatasi == 0 {
		fmt.Println("Hata: Log yazilamadi ->", hata)
	}
}

// Giris yapma fonksiyonu
func girisYap() *Kullanici {
	klavye := bufio.NewScanner(os.Stdin)

	// Kullanici tipini sec
	fmt.Println("\n=== Giris Ekrani ===")
	fmt.Println("Kullanici Tipini Secin:")
	fmt.Println("0 -> Admin")
	fmt.Println("1 -> Musteri")
	fmt.Println("2 -> Programdan Cik")

	var kullaniciTipi int
	var girdi string

	// Kullanici tipi secimi
	for {
		fmt.Print("Seciminiz: ")
		klavye.Scan()
		girdi = klavye.Text()

		if girdi == "" {
			fmt.Println("Hata: Secim bos birakilamaz!")
			continue
		}

		// Sayiya cevirme islemi
		sayi, hata := fmt.Sscanf(girdi, "%d", &kullaniciTipi)
		if hata != nil || sayi != 1 {
			fmt.Println("Hata: Lutfen sayi giriniz!")
			continue
		}

		if kullaniciTipi < 0 || kullaniciTipi > 2 {
			fmt.Println("Hata: Gecersiz secim! Lutfen 0, 1 veya 2 giriniz.")
			continue
		}

		if kullaniciTipi == 2 {
			logYaz("Program kapatildi")
			fmt.Println("\nProgram kapatiliyor... Iyi gunler!")
			os.Exit(0)
		}

		break
	}

	// Kullanici adi alma
	var kullaniciAdi string
	for {
		fmt.Print("Kullanici Adi: ")
		klavye.Scan()
		kullaniciAdi = klavye.Text()

		if kullaniciAdi == "" {
			fmt.Println("Hata: Kullanici adi bos birakilamaz!")
			continue
		}
		break
	}

	// Sifre alma
	var sifre string
	for {
		fmt.Print("Sifre: ")
		klavye.Scan()
		sifre = klavye.Text()

		if sifre == "" {
			fmt.Println("Hata: Sifre bos birakilamaz!")
			continue
		}
		break
	}

	// Kullaniciyi kontrol et
	for i := 0; i < len(kullaniciListesi); i++ {
		kullanici := kullaniciListesi[i]
		if kullanici.KullaniciAdi == kullaniciAdi && kullanici.Sifre == sifre && kullanici.Tip == kullaniciTipi {
			logYaz("Basarili: " + kullaniciAdi + " giris yapti")
			return &kullanici
		}
	}

	// Kullanici bulunamadi
	logYaz("Basarisiz giris denemesi -> Kullanici: " + kullaniciAdi)
	return nil
}

// Admin menusu
func adminMenusu(k *Kullanici) {
	klavye := bufio.NewScanner(os.Stdin)

	for {
		fmt.Println("\n=== Admin Menusu ===")
		fmt.Println("1 -> Musteri Ekle")
		fmt.Println("2 -> Musteri Sil")
		fmt.Println("3 -> Log Kayitlarini Goruntule")
		fmt.Println("4 -> Cikis")

		var secim int
		var girdi string

		// Menu secimi
		for {
			fmt.Print("Seciminiz: ")
			klavye.Scan()
			girdi = klavye.Text()

			if girdi == "" {
				fmt.Println("Hata: Secim bos birakilamaz!")
				continue
			}

			// Sayiya cevirme islemi
			sayi, hata := fmt.Sscanf(girdi, "%d", &secim)
			if hata != nil || sayi != 1 {
				fmt.Println("Hata: Lutfen sayi giriniz!")
				continue
			}

			if secim < 1 || secim > 4 {
				fmt.Println("Hata: Gecersiz secim! Lutfen 1-4 arasi bir sayi giriniz.")
				continue
			}

			break
		}

		// Secime gore islem yap
		switch secim {
		case 1:
			musteriEkle()
		case 2:
			musteriSil()
		case 3:
			loglariGoster()
		case 4:
			logYaz("Admin cikis yapti: " + k.KullaniciAdi)
			return
		}
	}
}

// Musteri menusu
func musteriMenusu(k *Kullanici) {
	klavye := bufio.NewScanner(os.Stdin)

	for {
		fmt.Println("\n=== Musteri Menusu ===")
		fmt.Println("1 -> Profil Bilgilerimi Goruntule")
		fmt.Println("2 -> Sifremi Degistir")
		fmt.Println("3 -> Cikis")

		var secim int
		var girdi string

		// Menu secimi
		for {
			fmt.Print("Seciminiz: ")
			klavye.Scan()
			girdi = klavye.Text()

			if girdi == "" {
				fmt.Println("Hata: Secim bos birakilamaz!")
				continue
			}

			// Sayiya cevirme islemi
			sayi, hata := fmt.Sscanf(girdi, "%d", &secim)
			if hata != nil || sayi != 1 {
				fmt.Println("Hata: Lutfen sayi giriniz!")
				continue
			}

			if secim < 1 || secim > 3 {
				fmt.Println("Hata: Gecersiz secim! Lutfen 1-3 arasi bir sayi giriniz.")
				continue
			}

			break
		}

		// Secime gore islem yap
		switch secim {
		case 1:
			profilGoruntule(k)
		case 2:
			sifreDegistir(k)
		case 3:
			logYaz("Musteri cikis yapti: " + k.KullaniciAdi)
			return
		}
	}
}

// Yeni musteri ekleme fonksiyonu
func musteriEkle() {
	klavye := bufio.NewScanner(os.Stdin)

	fmt.Println("\n=== Yeni Musteri Ekleme ===")
	fmt.Print("Musteri Kullanici Adi: ")
	klavye.Scan()
	kullaniciAdi := klavye.Text()

	// Kullanici adi bos mu diye kontrol et
	if kullaniciAdi == "" {
		fmt.Println("Hata: Kullanici adi bos birakilamaz!")
		return
	}

	// Ayni isimde kullanici var mi diye kontrol et
	for i := 0; i < len(kullaniciListesi); i++ {
		kullanici := kullaniciListesi[i]
		if kullanici.KullaniciAdi == kullaniciAdi {
			fmt.Println("Hata: Bu kullanici adi zaten kullaniliyor!")
			return
		}
	}

	fmt.Print("Musteri Sifresi: ")
	klavye.Scan()
	sifre := klavye.Text()

	// Sifre bos mu diye kontrol et
	if sifre == "" {
		fmt.Println("Hata: Sifre bos birakilamaz!")
		return
	}

	// Yeni musteriyi listeye ekle
	kullaniciListesi = append(kullaniciListesi, Kullanici{
		KullaniciAdi: kullaniciAdi,
		Sifre:        sifre,
		Tip:          1, // Musteri tipi
	})

	// Degisiklikleri kaydet
	hata := kullanicilariKaydet()
	if hata != nil {
		fmt.Println("Hata: Musteri kaydedilemedi ->", hata)
		return
	}

	logYaz("Yeni musteri eklendi -> " + kullaniciAdi)
	fmt.Println("Basarili: Musteri eklendi!")
}

// Musteri silme fonksiyonu
func musteriSil() {
	klavye := bufio.NewScanner(os.Stdin)

	fmt.Println("\n=== Musteri Silme ===")
	fmt.Print("Silinecek Musteri Kullanici Adi: ")
	klavye.Scan()
	kullaniciAdi := klavye.Text()

	// Musteriyi bul ve sil
	var bulundu bool = false
	for i := 0; i < len(kullaniciListesi); i++ {
		kullanici := kullaniciListesi[i]
		if kullanici.KullaniciAdi == kullaniciAdi && kullanici.Tip == 1 {
			// Slice'dan musteriyi cikar
			ilkKisim := kullaniciListesi[:i]
			sonKisim := kullaniciListesi[i+1:]
			kullaniciListesi = append(ilkKisim, sonKisim...)
			bulundu = true

			// Degisiklikleri kaydet
			hata := kullanicilariKaydet()
			if hata != nil {
				fmt.Println("Hata: Degisiklikler kaydedilemedi ->", hata)
				return
			}

			logYaz("Musteri silindi -> " + kullaniciAdi)
			fmt.Println("Basarili: Musteri silindi!")
			break
		}
	}

	if !bulundu {
		fmt.Println("Hata: Musteri bulunamadi!")
	}
}

// Log kayitlarini goruntuleme fonksiyonu
func loglariGoster() {
	fmt.Println("\n=== Log Kayitlari ===")

	// Log dosyasini oku
	icerik, hata := os.ReadFile("log.txt")
	if hata != nil {
		fmt.Println("Hata: Log dosyasi okunamadi ->", hata)
		return
	}

	// Kayitlari goster
	fmt.Println(string(icerik))
}

// Profil goruntuleme fonksiyonu
func profilGoruntule(k *Kullanici) {
	fmt.Println("\n=== Profil Bilgileri ===")
	fmt.Println("Kullanici Adi:", k.KullaniciAdi)
	fmt.Println("Kullanici Tipi:", map[int]string{0: "Admin", 1: "Musteri"}[k.Tip])
}

// Sifre degistirme fonksiyonu
func sifreDegistir(k *Kullanici) {
	klavye := bufio.NewScanner(os.Stdin)

	fmt.Println("\n=== Sifre Degistirme ===")
	fmt.Print("Mevcut Sifre: ")
	klavye.Scan()
	mevcutSifre := klavye.Text()

	// Mevcut sifreyi kontrol et
	if mevcutSifre != k.Sifre {
		fmt.Println("Hata: Mevcut sifre yanlis!")
		return
	}

	fmt.Print("Yeni Sifre: ")
	klavye.Scan()
	yeniSifre := klavye.Text()

	// Sifreyi guncelle
	var sifreDegisti bool = false
	for i := 0; i < len(kullaniciListesi); i++ {
		if kullaniciListesi[i].KullaniciAdi == k.KullaniciAdi {
			kullaniciListesi[i].Sifre = yeniSifre
			k.Sifre = yeniSifre
			sifreDegisti = true
			break
		}
	}

	if !sifreDegisti {
		fmt.Println("Hata: Sifre degistirilemedi!")
		return
	}

	// Degisiklikleri kaydet
	hata := kullanicilariKaydet()
	if hata != nil {
		fmt.Println("Hata: Sifre kaydedilemedi ->", hata)
		return
	}

	logYaz("Sifre degistirildi -> Kullanici: " + k.KullaniciAdi)
	fmt.Println("Basarili: Sifre degistirildi!")
}

// Ana program
func main() {
	defer logDosyasi.Close() // Program bitince log dosyasini kapat

	fmt.Println("\n=== Kullanici Giris Sistemi ===")
	fmt.Println("Hos Geldiniz!")

	// Sonsuz dongu: Programi surekli calisir tut
	for {
		kullanici := girisYap()
		if kullanici == nil {
			fmt.Println("\nHata: Giris basarisiz! Lutfen tekrar deneyin.")
			continue
		}

		fmt.Printf("\nMerhaba %s! Hos geldiniz!\n", kullanici.KullaniciAdi)

		// Kullanici tipine gore menuyu goster
		if kullanici.Tip == 0 {
			adminMenusu(kullanici)
		} else {
			musteriMenusu(kullanici)
		}
	}
}
