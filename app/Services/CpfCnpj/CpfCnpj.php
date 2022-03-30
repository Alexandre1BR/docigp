<?php

namespace App\Services\CpfCnpj;

/**
 * ValidaCPFCNPJ valida e formata CPF e CNPJ
 *
 * Exemplo de uso:
 * $cpf_cnpj  = new ValidaCPFCNPJ('71569042000196');
 * $formatted = $cpf_cnpj->formatAny(); // 71.569.042/0001-96
 * $valida    = $cpf_cnpj->valida(); // True -> Válido
 *
 * @package  valida-cpf-cnpj
 * @author   Luiz Otávio Miranda <contato@tutsup.com>
 * @author   Antonio Carlos Ribeiro (acr@antoniocarlosribeiro.com) - Improvements
 * @version  v1.4
 * @access   public
 */
class CpfCnpj
{
    const CPF_SIZE = 11;

    const CNPJ_SIZE = 14;

    const CPF_TYPE = 'CPF';

    const CNPJ_TYPE = 'CNPJ';

    /**
     * @var string|string[]|null
     */
    protected $number;

    /**
     * Configura o number (Construtor)
     *
     * Remove caracteres inválidos do CPF ou CNPJ
     *
     * @param string $number - O CPF ou CNPJ
     */
    function __construct($number = null)
    {
        // Deixa apenas números no number
        $this->number = preg_replace('/[^0-9]/', '', $number);

        // Garante que o number é uma string
        $this->number = (string) $this->number;
    }

    /**
     * Verifica se é CPF ou CNPJ
     *
     * Se for CPF tem 11 caracteres, CNPJ tem 14
     *
     * @access protected
     * @return string CPF, CNPJ ou false
     */
    protected function detect()
    {
        // Verifica CPF
        if (strlen($this->number) === self::CPF_SIZE) {
            return self::CPF_TYPE;
        }

        // Verifica CNPJ
        elseif (strlen($this->number) === self::CNPJ_SIZE) {
            return self::CNPJ_TYPE;
        }

        return false;
    }

    /**
     * Multiplica dígitos vezes posições
     *
     * @access protected
     * @param string $digits Os digits desejados
     * @param int $length A posição que vai iniciar a regressão
     * @param int $sum A soma das multiplicações entre posições e dígitos
     * @return int                     Os dígitos enviados concatenados com o último dígito
     */
    protected function calculateDigitsLength($digits, $length = 10, $sum = 0)
    {
        // Faz a soma dos dígitos com a posição
        // Ex. para 10 posições:
        //   0    2    5    4    6    2    8    8   4
        // x10   x9   x8   x7   x6   x5   x4   x3  x2
        //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
        for ($i = 0; $i < strlen($digits); $i++) {
            // Preenche a soma com o dígito vezes a posição
            $sum = $sum + $digits[$i] * $length;

            // Subtrai 1 da posição
            $length--;

            // Parte específica para CNPJ
            // Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
            if ($length < 2) {
                // Retorno a posição para 9
                $length = 9;
            }
        }

        // Captura o resto da divisão entre $sum dividido por 11
        // Ex.: 196 % 11 = 9
        $sum = $sum % 11;

        // Verifica se $sum é menor que 2
        if ($sum < 2) {
            // $sum agora será zero
            $sum = 0;
        } else {
            // Se for maior que 2, o resultado é 11 menos $sum
            // Ex.: 11 - 9 = 2
            // Nosso dígito procurado é 2
            $sum = 11 - $sum;
        }

        // Concatena mais um dígito aos primeiro nove dígitos
        // Ex.: 025462884 + 2 = 0254628842
        $cpf = $digits . $sum;

        // Retorna
        return $cpf;
    }

    /**
     * @return string|string[]|null
     */
    public function getNumber()
    {
        return $this->number;
    }

    private function validate()
    {
        if (!$this->detect()) {
            return false;
        }

        return $this->detect() === self::CPF_TYPE ? $this->validateCpf() : $this->validateCnpj();
    }

    /**
     * Valida CPF
     *
     * @param string $cpf O CPF com ou sem pontos e traço
     * @return bool           True para CPF correto - False para CPF incorreto
     * @author                Luiz Otávio Miranda <contato@tutsup.com>
     * @access protected
     */
    public function validateCpf()
    {
        // Captura os 9 primeiros dígitos do CPF
        // Ex.: 02546288423 = 025462884
        $digits = substr($this->number, 0, 9);

        // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
        $newCpf = $this->calculateDigitsLength($digits);

        // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
        $newCpf = $this->calculateDigitsLength($newCpf, 11);

        // Verifica se o novo CPF gerado é idêntico ao CPF enviado
        if ($newCpf === $this->number) {
            // CPF válido
            return true;
        } else {
            // CPF inválido
            return false;
        }
    }

    /**
     * Valida CNPJ
     *
     * @param string $cnpj
     * @return bool             true para CNPJ correto
     * @author                  Luiz Otávio Miranda <contato@tutsup.com>
     * @access protected
     */
    public function validateCnpj()
    {
        // O number original
        $originalCnpj = $this->number;

        // Captura os primeiros 12 números do CNPJ
        $firstCnpjNumbers = substr($this->number, 0, 12);

        // Faz o primeiro cálculo
        $primeiro_calculo = $this->calculateDigitsLength($firstCnpjNumbers, 5);

        // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
        $secondCalculus = $this->calculateDigitsLength($primeiro_calculo, 6);

        // Concatena o segundo dígito ao CNPJ
        $cnpj = $secondCalculus;

        // Verifica se o CNPJ gerado é idêntico ao enviado
        if ($cnpj === $originalCnpj) {
            return true;
        }
    }

    /**
     * Valida
     *
     * Valida o CPF ou CNPJ
     *
     * @access public
     * @return bool      True para válido, false para inválido
     */
    public function valida()
    {
        // Valida CPF
        if ($this->detect() === self::CPF_TYPE) {
            // Retorna true para cpf válido
            return $this->validateCpf();
        }

        // Valida CNPJ
        elseif ($this->detect() === self::CNPJ_TYPE) {
            // Retorna true para CNPJ válido
            return $this->validateCnpj();
        }

        return false;
    }

    /**
     * Formata CPF ou CNPJ
     *
     * @access public
     * @return string  CPF ou CNPJ formatted
     */
    public function formatAny()
    {
        // O number formatted
        $formatted = false;

        // Valida CPF
        if ($this->detect() === self::CPF_TYPE) {
            // Verifica se o CPF é válido
            if ($this->validateCpf()) {
                // Formata o CPF ###.###.###-##
                $formatted = substr($this->number, 0, 3) . '.';
                $formatted .= substr($this->number, 3, 3) . '.';
                $formatted .= substr($this->number, 6, 3) . '-';
                $formatted .= substr($this->number, 9, 2) . '';
            }
        }
        // Valida CNPJ
        elseif ($this->detect() === self::CNPJ_TYPE) {
            // Verifica se o CPF é válido
            if ($this->validateCnpj()) {
                // Formata o CNPJ ##.###.###/####-##
                $formatted = substr($this->number, 0, 2) . '.';
                $formatted .= substr($this->number, 2, 3) . '.';
                $formatted .= substr($this->number, 5, 3) . '/';
                $formatted .= substr($this->number, 8, 4) . '-';
                $formatted .= substr($this->number, 12, 14) . '';
            }
        }

        // Retorna o number
        return $formatted;
    }

    public static function check($number)
    {
        return (new static($number))->validate();
    }

    public static function type($number)
    {
        $checker = new static($number);

        return $checker->validate() ? $checker->detect() : false;
    }

    public static function generate($size)
    {
        $base = collect(range(1, $size - 2))
            ->map(function ($_) {
                return rand(0, 9);
            })
            ->implode('');

        foreach (range(0, 99) as $number) {
            if (static::check($final = $base . str_pad($number, 2, '0'))) {
                return $final;
            }
        }

        return static::check($final) ? $final : static::generate($size);
    }

    public static function generateCpf()
    {
        return (new static(static::generate(static::CPF_SIZE)))->formatAny();
    }

    public static function generateCnpj()
    {
        return (new static(static::generate(static::CNPJ_SIZE)))->formatAny();
    }

    public static function format($number)
    {
        return (new static($number))->formatAny();
    }

    public static function removeFormat($number)
    {
        return (new static($number))->getNumber();
    }

    public static function isCpf($number)
    {
        return static::type($number) === static::CPF_TYPE;
    }

    public static function isCnpj($number)
    {
        return static::type($number) === static::CNPJ_TYPE;
    }
}
