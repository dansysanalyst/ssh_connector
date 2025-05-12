<?php

declare(strict_types = 1);

namespace App\Support;

use App\Exceptions\SshException;
use App\Rules\{FileExistsRule, ValidHostRule};
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Stringable;

/**
 * SSH Server Configuration
 *
 * @property-read string $title
 * @property-read string $group
 * @property-read string $user
 * @property-read string $hostname
 * @property-read int $port
 * @property-read int|null $tunnelPort
 * @property-read string|null $keyfilePath
 */
final class Server
{
    private readonly string $id;

    public function __construct(
        private readonly string $title,
        private readonly string $group,
        private readonly string $user,
        private readonly string $hostname,
        private readonly int $port,
        private readonly ?int $tunnelPort,
        private readonly ?string $keyfilePath,
    ) {
        $this->id =  (string) str()->uuid();

        $this->validate();
    }

    /**
     * @param  array{'id': string, 'title': string, 'group': string, 'user': string, 'hostname': string, 'port': int, 'tunnel_port': int|null, 'keyfile': string|null}  $server
     */
    public static function fromArray(array $server): self
    {
        return new self(
            title: $server['title'],
            group: $server['group'],
            user: $server['user'],
            hostname: $server['hostname'],
            port: $server['port'],
            tunnelPort: $server['tunnel_port'],
            keyfilePath: $server['keyfile']
        );
    }

    /**
     * @return array<string, mixed>.
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function toPairedString(): string
    {
        return collect(get_object_vars($this))
            ->filter()
            ->map(fn ($value, $key) => strtoupper($key) . "={$value}")
            ->implode(',');
    }

    private function validate(): void
    {
        $server = $this->toArray();

        $validator = Validator::make($server, [
            'id'          => ['required', 'uuid'],
            'title'       => ['required', 'string', 'max:255'],
            'group'       => ['string', 'max:255'],
            'hostname'    => ['required', new ValidHostRule],
            'user'        => ['required', 'string', 'max:255'],
            'port'        => ['required', 'numeric'],
            'tunnelPort'  => ['nullable', 'numeric', 'gt:0'],
            'keyfilePath' => ['nullable', 'string', new FileExistsRule],
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors()->keys())
                ->mapWithKeys(function (string $error) use ($server): array {
                    return ["{$error}: {$server[$error]}"];
                })
                ->implode(', ');

            SshException::InvalidServerConfig($errors);
        }
    }

    public function hasKeyfile(): bool
    {
        return $this->keyfilePath != '';
    }

    public function hasTunnel(): bool
    {
        return intval($this->tunnelPort) > 0;
    }

    /**
     * @return array<string, string>
     */
    public function asMenuOption(): array
    {
        return [$this->id => $this->title . ($this->hasTunnel() ? ' 🤿 TUNNELED 🤿' : '')];
    }

    public function sshCommand(): string
    {
        return str('ssh')
            ->when($this->hasTunnel(), fn (Stringable $c): Stringable => $c->append(" -D {$this->tunnelPort}"))
            ->when($this->hasKeyfile(), fn (Stringable $c): Stringable => $c->append(" -i \"{$this->keyfilePath}\""))
            ->append(" -p {$this->port} {$this->user}@{$this->hostname}")
            ->replaceMatches('/\s+/', ' ')
            ->toString();
    }

    public function __get(string $propertyName): string|int|null
    {
        if (! property_exists($this, $propertyName)) {
            throw new \Exception('Invalid property name: ' . $propertyName);
        }

        return $this->$propertyName;
    }
}
