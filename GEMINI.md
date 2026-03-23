# Gemini Project Rules

## Communication and Reasoning
- Think and act in English, but always answer in Russian in the chat.

## Project Context & Rules
- Для проектов на Python всегда используй `uv`.
- Для проектов на PHP используй версию 8.x.

Always refer to the following rules before starting tasks:

@/home/keemor/.gemini/rules/agent-memory-recall.md
@/home/keemor/.gemini/rules/agent-model-selection.md
@/home/keemor/.gemini/rules/claim-verification.md
@/home/keemor/.gemini/rules/cross-terminal-db.md
@/home/keemor/.gemini/rules/destructive-commands.md
@/home/keemor/.gemini/rules/dynamic-recall.md
@/home/keemor/.gemini/rules/hook-auto-execute.md
@/home/keemor/.gemini/rules/no-haiku.md
@/home/keemor/.gemini/rules/proactive-delegation.md
@/home/keemor/.gemini/rules/proactive-memory-disclosure.md
@/home/keemor/.gemini/rules/tldr-cli.md
@/home/keemor/.gemini/rules/use-scout-not-explore.md

## Agent Guidelines
- Use the `generalist` tool to spawn specialized agents located in `.gemini/agents/*.md`.
- Follow the TDD workflow for all implementation tasks.
- Always use the `tldr` tool for large codebases to save tokens.
