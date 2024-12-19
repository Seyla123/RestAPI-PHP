<?php 
    class JWTCodec
    {
        public function encode(array $payload): string
        {
            $header = json_encode([
                "typ"=> "JWT",
                "alg"=> "HS256"
            ]);
            $header = $this->base64urlEncode($header);

            $payload = json_encode($payload);
            $payload = $this->base64urlEncode($payload);

            $signature = hash_hmac(
            "sha256", 
            "$header.$payload", 
            "4f6c2d3c4b7a1e9f5c3d2e1b0a4f5e9d6c8a3b2e1f4d5c9b3a2e1f8c7d6e5a4b", 
            true);
            $signature = $this->base64urlEncode($signature);

            return "$header.$payload.$signature";
        }

        public function base64urlEncode(string $text):string
        {
            return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
        }
    }
?>