<?php declare(strict_types=1);

require_once 'config.php';

class TagOmissions extends Extension
{
    public function onDatabaseUpgrade(DatabaseUpgradeEvent $event)
    {
        global $database;

        if ($this->get_version(TagOmissionsConfig::VERSION) < 1) {
            $database->create_table("tag_omissions", "
                    tag_id INTEGER NOT NULL,
                    omission_id INTEGER NOT NULL,
                    symmetric BOOLEAN NOT NULL DEFAULT TRUE,
                    UNIQUE(tag_id, omission_id),
                    CHECK(tag_id < omission_id),
                    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
                    FOREIGN KEY (omission_id) REFERENCES tags(id) ON DELETE CASCADE,
                ");
            $database->execute("CREATE INDEX tag_omissions_tag_id_idx ON tag_omissions(tag_id)");
            $database->execute("CREATE INDEX tag_omissions_omission_id_idx ON tag_omissions(omission_id)");

            $this->set_version(TagOmissionsConfig::VERSION, 1);

            log_info(TagOmissionsInfo::KEY, "extension installed");
        }
    }

    public function get_omissions(Array $tag_ids)
    {
        global $database;

        $selection = "(".implode(",", $tag_ids).")";

        $omissions = $database->get_col("
                SELECT DISTINCT
                    CASE WHEN tag_id IN $selection THEN omission_id ELSE tag_id END AS omissions
                FROM tag_omissions
                WHERE (CASE WHEN symmetric = 1 THEN
                    tag_id IN $selection OR omission_id IN $selection
                ELSE
                    tag_id IN $selection
                END)
                ORDER BY omissions ASC
            ");

        return $omissions;
    }
}