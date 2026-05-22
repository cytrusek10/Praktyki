<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@blog.pl',
            'password' => Hash::make('password'),
        ]);

        $posts = [
            [
                'title'        => 'Dlaczego kawa o 23:00 to zawsze zły pomysł',
                'category'     => 'zycie',
                'excerpt'      => 'I dlaczego mimo to za każdym razem to robię. Analiza życiowej głupoty.',
                'content'      => '<p>No więc znowu to zrobiłem. Godzina 23:00, deadline jutro, a ja stwierdzam że kawa to dobry pomysł. Spoiler alert: nie jest.</p>
<p>Po pierwsze, kawa o tej porze działa jakby miała wbudowany timer – dokładnie kiedy chcesz iść spać, twój mózg postanawia przetworzyć wszystkie decyzje życiowe od urodzenia do teraz. Dlaczego zrobiłem to w podstawówce? Czy tamten kolega z trzeciej klasy to na mnie był obrażony? Ważne pytania, 3 w nocy, nie mogę spać.</p>
<p>Po drugie, nawet jeśli jakoś zaśniesz, wstajesz wyglądając jak zombie po tym jak ktoś opowiedział mu smutną historię. Worki pod oczami? Tak. Rozum? Nie.</p>
<p>Ale i tak jutro pewnie znowu to zrobię, bo deadline i bo czemu nie.</p>',
                'published'    => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title'        => 'Recenzja pizzy z miejsca, które wyglądało podejrzanie',
                'category'     => 'jedzenie',
                'excerpt'      => 'Zjadłem pizzę z miejsca, które miało jedną gwiazdkę na Google. Oto raport.',
                'content'      => '<p>Więc jest taka pizzeria niedaleko mnie. Jedna gwiazdka na Google Maps. Jeden komentarz, który mówi tylko "było". Logika nakazywała mi pójść gdzie indziej, ale coś mnie ciągnęło.</p>
<p>Lokal od zewnątrz wygląda jak gdyby ktoś otworzył restaurację w ramach zakładu. Tapeta z lat 90, jedno okno, napis "pizza" napisany odręcznie na kartce A4.</p>
<p>Ale pizza... pizza była niesamowita. Serio. Ser ciągnął się jak marzenie, sos był wyraźnie domowy, ciasto idealne. Zjadłem dwie i żałuję że nie wziąłem trzeciej.</p>
<p><strong>Wniosek:</strong> jedyna gwiazdka jest prawdopodobnie od kogoś kto nie rozumie jedzenia. Polecam z czystym sumieniem, choć nie gwarantuję że przeżyjesz wizytę do toalety.</p>',
                'published'    => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title'        => 'Jak spędziłem 6 godzin szukając buga, który był przecinkiem',
                'category'     => 'technologia',
                'excerpt'      => 'Historia o tym jak jeden znak potrafi zniszczyć twój dzień i wiarę w siebie.',
                'content'      => '<p>Byłem przekonany że napisałem świetny kod. Działało lokalnie, testy przechodziły, wszystko cacy. Deploy na serwer i... nic. Błąd 500. Biały ekran śmierci.</p>
<p>Spędziłem dwie godziny przeglądając logi. Trzy godziny googlując error message który wyskakiwał. Jedna godzina pytając kolegę który tylko wzruszył ramionami.</p>
<p>Szósta godzina. Już prawie się poddałem i postanowiłem przepisać cały plik. I wtedy zobaczyłem to. W linijce 47. Przecinek zamiast kropki. Jeden. Malutki. Przecinek.</p>
<p>Naprawiłem. Działa. Nie powiedziałem nikomu przez tydzień. Teraz powiem wam. Programowanie to ból ale też szczęście, bo jednak zadziałało.</p>',
                'published'    => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title'        => 'Playlisty na różne sytuacje życiowe których nie planowałeś',
                'category'     => 'muzyka',
                'excerpt'      => 'Muzyka do siedzenia w korku gdy jesteś już spóźniony i do czekania na tę osobę.',
                'content'      => '<p>Muzyka to coś więcej niż tło. To pełnoprawny uczestnik każdej sytuacji. Dlatego stworzyłem nieoficjalny przewodnik:</p>
<h2>Korki na obwodnicy, już jesteś 30 minut spóźniony</h2>
<p>Polecam coś wolnego i żałosnego. Nie żeby poprawić humor, ale żeby w pełni wczuć się w dramat sytuacji. Pink Floyd działa doskonale.</p>
<h2>Sklep, 17:00, wszyscy robią zakupy, jest tłoczno i głośno</h2>
<p>Słuchawki, cokolwiek z sensem, głośno. Tworzysz własną bańkę. Świat nie istnieje.</p>
<h2>Gotowanie o północy bo zapomniałeś zjeść obiad</h2>
<p>Lo-fi hip hop. Klasyker. Działa za każdym razem. Nagle gotowanie staje się artystycznym doświadczeniem, a nie koniecznością biologiczną.</p>',
                'published'    => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title'        => 'Próbowałem biegać przez tydzień. Oto co się stało',
                'category'     => 'sport',
                'excerpt'      => 'Dziennik eksperymentu człowieka, który normalnie biega tylko gdy spóźnia się na autobus.',
                'content'      => '<p>Dzień 1: Wyszedłem z domu pełen zapału. Przebiegłem jakieś 800 metrów. Wróciłem do domu chodząc, robiąc głośne oddychanie. Sąsiadka się zaniepokoiła.</p>
<p>Dzień 2: Mięśnie protestowały. Jakby ktoś podmienił moje nogi na wersje demo, które działają tylko 20% sprawności. Wyszedłem mimo to. Przebiegłem kilometr. Prawie.</p>
<p>Dzień 3: Odpoczynek. Ale planowany odpoczynek, nie z lenistwa. To różnica.</p>
<p>Dzień 5: Coś kliknęło. Przebiegłem 2 kilometry i nie chciałem umrzeć. To chyba postęp.</p>
<p>Dzień 7: 3 kilometry. Wolno, niezgrabnie, ale 3 kilometry. Kontynuuję eksperymenty.</p>
<p><strong>Wniosek:</strong> bieganie jest do niczego przez pierwsze trzy dni, a potem jest tylko trochę do niczego. Polecam.</p>',
                'published'    => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title'        => 'Ranking najlepszych momentów kiedy internet przestaje działać',
                'category'     => 'inne',
                'excerpt'      => 'Badanie naukowe przeprowadzone przeze mnie na podstawie własnego cierpienia.',
                'content'      => '<p>Po latach badań terenowych (aka własne życie) stworzyłem ranking:</p>
<h2>Miejsce 1: Dokładnie gdy wysyłasz ważny email</h2>
<p>Klasyk. Wpisujesz, klikasz wyślij, loading... spinning... brak połączenia. Czy wysłało? Nie wiadomo. Wysyłasz ponownie? Może dojdzie dwa razy. Nie wysyłasz? Może nie doszło. Stres.</p>
<h2>Miejsce 2: W połowie streama</h2>
<p>Film w najlepszym miejscu. Bufferowanie. Czekasz. Wróciło. Zbuforować do samego końca? Nie. Oczywiście że nie.</p>
<h2>Miejsce 3: Gdy ktoś dzwoni przez internet</h2>
<p>"Hej, słyszysz mnie?" "Tak, ale urwało się gdy mówiłeś o-" "Co?" "Nic." Rozmawiam z głuchą przestrzenią przez 45 minut.</p>
<p>Internet jest wynalazkiem wspaniałym dopóki nie przestaje działać, co robi regularnie i zawsze w złym momencie.</p>',
                'published'    => true,
                'published_at' => now()->subDays(14),
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
