import { describe, expect, it } from 'vitest';
import {
    displayEntryContent,
    displayEntryTitle,
    displayNumber,
    displayObjectKey,
    entryFieldIsEmail,
} from '@/helpers.js';

describe('display number', () => {
    it('should display a number as a string', () => {
        expect(displayNumber(42)).toBe('42');
    });

    it('should display the number in a human readable format', () => {
        expect(displayNumber(1_000)).toBe('1,000');
    })

    it('should display a maximum value after a cutoff point', () => {
        expect(displayNumber(1_000, 999)).toBe('999+');
    });
});

describe('display object key', () => {
    it('should display an object key in a human readable format', () => {
        expect(displayObjectKey('email')).toBe('Email');
    });

    it('should split an object key into capitalized words', () => {
        expect(displayObjectKey('contact_email')).toBe('Contact Email');
        expect(displayObjectKey('user-device-name')).toBe('User Device Name');
    });
});

describe('entry field is email', () => {
    it('should return true when the field key and value denote an email address', () => {
        expect(entryFieldIsEmail('email', 'johndoe@example.com')).toBe(true);
        expect(entryFieldIsEmail('contact_email', 'johndoe@example.com')).toBe(true);
    });

    it('should return false when the field key does not denote an email address', () => {
        expect(entryFieldIsEmail('message', 'Lorem ipsum dolor sit amet.')).toBe(false);
        expect(entryFieldIsEmail('message', 'johndoe@example.com')).toBe(false);
    });

    it('should return false when the field value does not match an email address', () => {
        expect(entryFieldIsEmail('send_email', true)).toBe(false);
        expect(entryFieldIsEmail('email', 'not an email address')).toBe(false);
    });
});
