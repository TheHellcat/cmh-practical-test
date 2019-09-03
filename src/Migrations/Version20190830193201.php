<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20190830193201
 * @package DoctrineMigrations
 */
final class Version20190830193201 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        if ($this->connection->getDatabasePlatform()->getName() === 'mysql') {
            $this->addSql('INSERT INTO `user_api_token` (`uid`, `name`, `api_token`, `roles`, `password`) VALUES (\'3726d6ee-cb5d-11e9-a92d-0242ac110003\',\'test-user\',\'TEST00000\',\'\',\'\'),(\'4be10ee2-cb5d-11e9-8a1b-0242ac110003\',\'test-user-2\',\'a3d27374d33385ec076d530684e6c51d0dc8a29cb1ad55139e3c5bcb5dd4dce7\',\'\',\'\');');
            $this->addSql('INSERT INTO `horse` (`id`, `name`, `picture_url`)
                            VALUES
                                (\'38168e7c-cb5e-11e9-bd71-0242ac110002\',\'Rainbow Dash\',\'http://cdn.ponies.com/raindow.jpg\'),
                                (\'43ad8709-cb5e-11e9-bd71-0242ac110002\',\'Pinky Pie\',\'http://cdn.ponies.com/pinky.jpg\'),
                                (\'50922f28-cb5e-11e9-bd71-0242ac110002\',\'Fluttershy\',\'http://cdn.ponies.com/fluttershy.jpg\'),
                                (\'64fda992-cb5e-11e9-bd71-0242ac110002\',\'Apple Jack\',\'http://cdn.ponies.com/apple.jpg\');');
        }
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
