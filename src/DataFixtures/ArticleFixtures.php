<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $articlesData = [
            [
                'titre' => 'Moji x Sboy : une alchimie artistique envoûtante',
                'slug' => 'moji-sboy-alchimie-artistique',
                'artiste' => 'Moji x Sboy',
                'categorie' => 'Découverte',
                'datePublication' => new \DateTimeImmutable('2023-04-20'),
                'contenu' => 'Le duo belge Moji x Sboy s’impose comme une révélation musicale incontournable. Portés par une sensibilité rare et des productions planantes, ils touchent un public en quête d’émotions brutes. Leur univers oscille entre mélancolie, amour et errance intérieure, porté par une écriture poétique et une sincérité touchante. Leur popularité ne cesse de croître grâce à une esthétique visuelle soignée et une présence scénique intense.',
            ],
            [
                'titre' => 'PLK : entre technique et accessibilité, une ascension maîtrisée',
                'slug' => 'plk-technique-accessibilite',
                'artiste' => 'PLK',
                'categorie' => 'Analyse',
                'datePublication' => new \DateTimeImmutable('2023-05-10'),
                'contenu' => 'PLK s’est imposé comme l’un des rappeurs les plus techniques de sa génération tout en conservant une accessibilité qui séduit un large public. Avec des punchlines percutantes, une voix reconnaissable entre mille et des instrumentales efficaces, il maîtrise à la perfection les codes actuels du rap tout en apportant une authenticité qui fait sa force.',
            ],
            [
                'titre' => 'Damso : introspection, poésie et puissance',
                'slug' => 'damso-introspection-poesie',
                'artiste' => 'Damso',
                'categorie' => 'Analyse',
                'datePublication' => new \DateTimeImmutable('2023-06-01'),
                'contenu' => 'Chaque sortie de Damso est un événement. L’artiste belge mêle introspection, noirceur et poésie dans un univers unique où chaque mot est soigneusement pesé. Ses albums sont autant de voyages intérieurs qui explorent l’amour, la douleur, la société et la spiritualité. Damso parvient à conjuguer exigence artistique et succès commercial, ce qui le place parmi les figures majeures du rap francophone.',
            ],
            [
                'titre' => 'Laylow : entre technologie et humanité',
                'slug' => 'laylow-technologie-humanite',
                'artiste' => 'Laylow',
                'categorie' => 'Interview',
                'datePublication' => new \DateTimeImmutable('2023-07-15'),
                'contenu' => 'Avec son univers cybernétique et futuriste, Laylow redéfinit les contours du rap moderne. Dans une interview exclusive, il revient sur sa vision artistique où l’homme et la machine coexistent en harmonie. Son approche narrative et visuelle témoigne d’une recherche permanente d’innovation, tant sur le fond que sur la forme. Laylow incarne une génération d’artistes qui refusent les carcans pour mieux explorer les possibles.',
            ],
            [
                'titre' => 'Les tendances musicales de 2023',
                'slug' => 'tendances-musicales-2023',
                'artiste' => 'Artiste A',
                'categorie' => 'Analyse',
                'datePublication' => new \DateTimeImmutable('2023-01-15'),
                'contenu' => 'Un aperçu des tendances musicales qui marquent l\'année 2023. Cette année, on observe une montée en puissance des sonorités électroniques mêlées à des influences urbaines, créant un mélange innovant qui séduit un public toujours plus large. Les artistes explorent également de nouvelles formes narratives dans leurs textes, abordant des thèmes sociétaux avec une sensibilité renouvelée. Les plateformes de streaming jouent un rôle clé dans la diffusion de ces nouvelles tendances, favorisant la découverte et la diversité musicale.',
            ],
            [
                'titre' => 'Interview exclusive avec Artiste B',
                'slug' => 'interview-exclusive-artiste-b',
                'artiste' => 'Artiste B',
                'categorie' => 'Interview',
                'datePublication' => new \DateTimeImmutable('2023-02-10'),
                'contenu' => 'Découvrez les coulisses de la carrière d\'Artiste B dans cette interview exclusive. L\'artiste revient sur ses débuts, ses inspirations et les défis rencontrés au fil de son parcours. Il partage également sa vision de l\'industrie musicale actuelle et ses projets futurs. Avec une sincérité touchante, Artiste B évoque l\'importance de rester fidèle à soi-même tout en évoluant dans un environnement en constante mutation. Une conversation riche en anecdotes et en réflexions profondes.',
            ],
            [
                'titre' => 'Top 10 des albums à écouter en 2023',
                'slug' => 'top-10-albums-2023',
                'artiste' => 'Divers artistes',
                'categorie' => 'Classement',
                'datePublication' => new \DateTimeImmutable('2023-03-05'),
                'contenu' => 'Une sélection des meilleurs albums à ne pas manquer cette année. Ce classement met en lumière une diversité d\'univers musicaux, allant du rap à la pop en passant par la musique électronique et les sonorités indie. Chaque album sélectionné se distingue par une production soignée, des textes engageants et une originalité qui capte l\'attention. Que vous soyez amateur de nouveautés ou à la recherche de pépites, cette liste vous guidera dans vos découvertes musicales pour 2023.',
            ],
        ];

        foreach ($articlesData as $data) {
            $article = new Article();
            $article->setTitre($data['titre']);
            $article->setSlug($data['slug']);
            $article->setArtiste($data['artiste']);
            $article->setCategorie($data['categorie']);
            $article->setDatePublication($data['datePublication']);
            $article->setContenu($data['contenu']);

            $manager->persist($article);
        }

        $manager->flush();
    }
}