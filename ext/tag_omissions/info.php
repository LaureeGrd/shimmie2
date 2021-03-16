<?php declare(strict_types=1);

class TagOmissionsInfo extends ExtensionInfo
{
    public const KEY = "tag_omissions";

    public string $key = self::KEY;
    public string $name = "[BETA] Tag Omissions";
    public array $authors = ["Laureano Passafaro"=>"laureegrd@gmail.com"];
    public string $license = self::LICENSE_GPLV2;
    public string $description = "Stop redundant or contradictory tags from appearing on the 'Related Tags' block.";
    public ?string $documentation =
"<p>WIP. No page available yet to modify tag omissions. It has to be done manually in the database.</p>
";
}
