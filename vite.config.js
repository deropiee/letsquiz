import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

// Dynamically gather all .css and .js files inside resources/css and resources/js
function gatherAssetEntries() {
    const roots = [
        { dir: 'resources/css', ext: '.css' },
        { dir: 'resources/js', ext: '.js' },
    ];

    const entries = [];
    for (const { dir, ext } of roots) {
        try {
            const files = fs.readdirSync(path.resolve(__dirname, dir));
            for (const file of files) {
                if (file.endsWith(ext)) {
                    entries.push(path.posix.join(dir.replace(/\\/g, '/'), file));
                }
            }
        } catch (e) {
            // If a directory doesn't exist, skip it (shouldn't happen in normal workflow)
        }
    }
    return entries;
}

const assetInputs = gatherAssetEntries();

export default defineConfig({
    plugins: [
        laravel({
            // Automatically includes every JS & CSS file in the two resource folders
            input: assetInputs,
            refresh: true,
        }),
    ],
});
