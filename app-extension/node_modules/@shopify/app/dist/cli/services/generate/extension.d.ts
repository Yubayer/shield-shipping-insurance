import { AppInterface } from '../../models/app/app.js';
import { GenerateExtensionContentOutput } from '../../prompts/generate/extension.js';
import { ExtensionTemplate } from '../../models/app/template.js';
import { DependencyVersion } from '@shopify/cli-kit/node/node-package-manager';
export interface GenerateExtensionTemplateOptions {
    app: AppInterface;
    cloneUrl?: string;
    extensionChoices: GenerateExtensionContentOutput[];
    extensionTemplate: ExtensionTemplate;
}
export type ExtensionFlavorValue = 'vanilla-js' | 'react' | 'typescript' | 'typescript-react' | 'rust' | 'wasm' | 'liquid' | 'config-only';
export type TemplateLanguage = 'javascript' | 'rust' | 'wasm' | undefined;
export interface GeneratedExtension {
    directory: string;
    extensionTemplate: ExtensionTemplate;
}
export declare function generateExtensionTemplate(options: GenerateExtensionTemplateOptions): Promise<GeneratedExtension[]>;
export declare function getFunctionRuntimeDependencies(templateLanguage: string): DependencyVersion[];
