# Tech Lead Interview Challenge

A three-stage interview to assess system design, implementation, and code review / pair programming. Based on the [Async PHP Coding Exercise](https://orchard.atlassian.net/wiki/spaces/PAP/pages/2217227276) (classroom attendance service). The candidate is briefed in advance so they know the theme; no preparation or pre-work is required.

---

## Pre-Interview Brief (send to candidate ahead of time)

**Theme:** You'll be working through the design and implementation of a **service to take classroom attendance each morning**. The service must be built in PHP (any framework or tools). It should meet an SLO of **99.99% success rate** and **99.99% of responses within 300ms**. We value testability, simplicity, security, and observability.

**What we'll do:**

1. **Whiteboard** – You'll sketch how you'd design the flow and components (no code).
2. **Coding** – You'll implement (or prompt) an initial implementation for one part of the system. **You must come with your own environment ready to work**: an IDE (e.g. Cursor, PHPStorm) and a project that is ready for coding – for example a bootstrapped application or framework (e.g. Symfony) where you have not yet written any application code. You will screen share your IDE and start prompting or coding from that state. We will not provide an environment for this stage.
3. **PR review** – You'll review a pull request that implements a related piece of functionality; we'll discuss what you'd improve and pair on changes.

You won't be expected to have designed a system in advance or to finish implementing it in our session. The goal of the exercises is to see how you think, plan, communicate and work.

---

## Stage 1: Whiteboarding (≈25–35 min)

### Goal

- See if they can turn the attendance-service brief into a clear diagram and component breakdown.
- Light requirements clarification: enough to show they think about scope and constraints (and the 300ms / 99.99% SLOs), without long discovery.

### Prompt to candidate

> "Design and build a service to take classroom attendance each morning. It must be in PHP, and it has to meet 99.99% success rate and 99.99% of responses within 300ms. Walk us through how you'd design this: what components are involved, how data flows, and how you'd meet the SLOs while keeping it testable, simple, secure, and observable. Draw it as you go."

### Optional requirements-gathering (5–10 min)

If they jump straight to drawing, gently add:

- "What would you need to clarify with product or school staff before locking this in?"
- "How do you interpret 'take attendance' – who triggers it, what's stored, and what happens if many classes hit the service at once?"
- "How does the 300ms constraint shape your design (e.g. sync vs async)?"

**What to look for**

| Area | Strong signals | Watch out for |
|------|----------------|---------------|
| **System design** | Clear boxes (teacher/client, API, storage, any async or external systems they choose); arrows for data/events; readable and consistent | Overly vague diagrams; no separation between fast path and heavy work |
| **PHP architecture** | Within components, shows or describes class structures, interfaces, and design patterns (e.g. repository, service layer, DTOs, dependency injection); thinks in terms of PHP types and boundaries | Only boxes and arrows; no mention of how the PHP code would be structured or which patterns to use |
| **SLOs** | Explains how 300ms is met (e.g. quick write + async processing, or read-heavy vs write path); mentions reliability (retries, idempotency, failure handling) | Ignores SLOs; or only "make it fast" with no concrete mechanism |
| **Components** | Bounded components (e.g. submission API, persistence, any background or external processing they propose); clear responsibility per part | Monolith blob; "attendance service" with no internal structure |
| **Consider** | Touches testability (injectable deps, test doubles), simplicity, security (authz, input validation), observability (logging, metrics, tracing) | Doesn't mention testing, security, or observability |
| **Requirements** | Asks 1–2 sensible questions (e.g. who can submit, what counts as "morning", scale) | Either no questions or long, unfocused discovery |
| **Tech lead behaviour** | Explains trade-offs (e.g. sync vs async, choice of persistence or offload); considers operability and deployment | Purely theoretical; no mention of how it's run or tested |

### Interviewer guidance

- Let them drive the diagram; only step in if they're stuck or if time is short.
- If they focus only on system-level boxes and arrows, ask: "Within the [submission API / persistence / whatever component they drew] component, how would you structure the PHP code – what classes, interfaces, or patterns would you use?"
- If they're very quick, ask: "How would you add a new consumer of attendance data (e.g. reporting) without changing the core submission path?"
- Note how they respond to SLO, "consider," and PHP architecture: do they refine the design or leave gaps?

---

## Stage 2: Coding exercise (≈25–35 min)

### Goal

- We **expect candidates to use AI** (e.g. Cursor, Copilot) where it helps. Assess whether they give **clear briefs that explain the system well** so the agent (or a human) can implement to their intent.
- Assess whether they **judge the output** in terms of: adherence to their vision, clear separation of concerns, naming, and good standards – and whether they **interact with the agent to hone in on their design** when the output doesn't match.
- Towards the end, we want to see that they consider testing; if they haven't, nudge them to do so.

### Scenario

The **candidate brings their own environment**: IDE and a project ready for coding (e.g. a bootstrapped Symfony app with no application code written yet). They screen share and implement (or prompt) in the session. We do not provide an environment. **We expect them to use AI**; clear briefs that explain the system and the desired structure are important.

Give the candidate this task (architecture is not specified – they choose how to meet the SLO):

- **Record attendance:** "Implement the part that records attendance for a class: given a class id, a date, and a list of students with present/absent, validate the input and persist or hand off the data so the request meets the 300ms SLO. How you meet that – e.g. a direct write, delegating to a queue, or another approach – is up to you. Use a persistence or external dependency that you can inject so it's testable."

They use their own IDE and project (PHP and Composer; stubs for any external or persistence dependencies as needed). They can prompt an AI pair, write pseudocode, or code directly.

### Acceptance criteria (for the candidate)

Share these so they know what "done" looks like:

1. **Behaviour**
   - Given a class id, date, and list of student attendance, the code validates input (e.g. non-empty, valid ids) and records or hands off the data in a way that keeps the response within the 300ms SLO.
   - Invalid or duplicate data is handled without crashing (e.g. validation failure or graceful skip).

2. **Structure**
   - The "record attendance" behaviour lives in a dedicated class with a clear name (e.g. `AttendanceService` or similar).
   - Dependencies (persistence, any external client, config) are injectable (constructor or method params), not hard-coded.

3. **Testing**
   - At least one unit test that verifies the main behaviour using test doubles (e.g. a mock for persistence or the external dependency) so the test doesn't hit real I/O.

4. **Consider (brief)**
   - How would you keep this simple and testable? How would you add security (e.g. who can submit) and observability (e.g. logging or metrics) in a real version?

### What to look for

| Area | Strong signals | Watch out for |
|------|----------------|---------------|
| **Briefs / system explanation** | Clear prompts or instructions that explain the system, boundaries, and desired structure so the agent (or a dev) can implement to their vision | Vague or minimal briefs; no context about separation of concerns or naming |
| **Judging the output** | Reviews agent output for adherence to their vision; flags poor separation of concerns, weak naming, or bad standards and asks for changes | Accepts first output without critique; doesn't check alignment with their design |
| **Interaction with the agent** | Refines prompts and gives follow-up instructions to hone in on the design (e.g. "use an interface here", "this should be a separate class") | One-shot prompts; no iteration when the output drifts from their intent |
| **Separation of concerns** | Clear boundaries (e.g. service vs repository); one responsibility per class; dependencies injected | Mixed concerns; hard-coded infrastructure; "new" of concrete classes in business logic |
| **Naming and standards** | Consistent, clear class and method names; follows good PHP/project standards | Unclear or inconsistent naming; ignores structure or style |
| **SLO** | Response meets 300ms (however they've chosen to achieve it); no unnecessary blocking work in the request path | Blocking the request on heavy work with no thought to the 300ms constraint |
| **Testing** | Considers or adds at least one unit test with doubles; describes behaviour | No mention of testing; only integration-style tests; if they haven't considered it by the end, **nudge them** |

### Interviewer guidance

- Observe **how they brief the agent** and **how they react to the output**: do they judge it against their vision and ask for refinements (separation of concerns, naming, standards)?
- If they haven't **considered testing** towards the end of the section, nudge them: e.g. "How would you test this? What would you mock?"
- If they're stuck on "how to inject," you can suggest: "How would you pass in the persistence (or external dependency) so we can test without hitting the real system?"
- Avoid leading them to a specific design; reward clear intent, good briefs, and iterating with the agent to get there.

---

## Stage 3: PR review / pair programming (≈25–35 min)

### Goal

- See if they spot missing abstraction (no dependency inversion) and weak test coverage in a PR that touches the same problem space.
- See if they can articulate improvements and, optionally, pair on a small fix.

### The "bad" PR

Provide a PR that implements **one** of the following (or a close variant), written so it deliberately violates good design and testing:

**Example: "Record attendance for a class (mark students present/absent)"**

- A controller or service method that:
  - Instantiates a concrete `AttendanceRepository` or `DatabaseConnection` (e.g. `new AttendanceRepository($pdo)`), and/or uses a static or global.
  - Contains validation and persistence in the same class with no clear boundary (e.g. "validate then save" in one place with no interface for persistence).
- No interface for the repository or persistence layer; high-level code depends on a concrete implementation.
- Unit tests that:
  - Only cover the happy path (e.g. "when all students are valid and save succeeds").
  - Don't cover failure (DB error), invalid input (empty class, invalid student id), or duplicate submission.
  - Or: tests that hit a real DB (integration-only) and no unit tests for the orchestration logic using a mock repository.

So the PR should demonstrate:

1. **Lack of dependency inversion** – direct use of concrete repository/DB class, or static/global, so the flow is hard to unit test and to swap storage later.
2. **Partial unit testing** – missing edge cases, or no unit tests that use doubles for the repository or persistence.

### Prompt to candidate

> "Here's a PR that adds [e.g. recording classroom attendance for a given class and date]. Review it as you would in a normal workflow: comment on design, test coverage, and anything you'd change. We'll then discuss and, if there's time, pair on one improvement."

### What to look for

| Area | Strong signals | Watch out for |
|------|----------------|---------------|
| **Dependency inversion** | Identifies that the repository (or persistence) should be behind an interface and injected; mentions testability and swapping implementations | Only style/naming feedback; doesn't mention interfaces or injection |
| **Testing** | Points out missing edge cases, or that unit tests should double the repository; suggests concrete cases (e.g. DB fails, invalid input, duplicate) | Only "add more tests" without saying what or why |
| **Clarity** | Explains *why* a change would help (e.g. "so we can test without a real DB") | Vague or purely stylistic comments |
| **Prioritisation** | Focuses on design and test gaps first; doesn't get lost in minor style nits | Only comma/formatting; ignores structure and tests |
| **Pairing (if done)** | Makes a small, safe change (e.g. extract interface, add one test) without breaking the flow | Big refactor; changing behaviour instead of structure/tests |

### Interviewer guidance

- Start with "What's your overall impression?" then "What would you improve first?"
- If they don't mention dependency inversion, ask: "How would you unit test this without a real database?"
- If they don't mention test gaps, ask: "What scenarios aren't covered by the current tests?"
- For pairing: suggest "Extract an interface for the repository and add one unit test that uses a mock" so the task is bounded.

---

## Summary: flow and timing

| Stage | Duration | Candidate outcome | Interviewer focus |
|-------|----------|-------------------|-------------------|
| 1. Whiteboard | ~25–35 min | Diagram + optional requirements clarification | System design + PHP architecture (class structures, design patterns within components), SLOs, consider (testability, security, observability) |
| 2. Coding | ~25–35 min | Implementation that meets ACs; use of AI with clear briefs; judging output and honing design with the agent | Clear briefs, judging output (vision, separation of concerns, naming, standards), agent interaction, testing (nudge if not considered) |
| 3. PR review | ~25–35 min | Review comments + optional small pairing | Spotting missing DIP and partial tests; actionable, prioritised feedback |

Total: about 1.5–2 hours including short intros and handoffs between stages.

---

## Checklist for interviewers (before the day)

- [ ] Candidate has received the pre-interview brief (theme: classroom morning attendance service, SLOs, three stages, no prep required).
- [ ] Whiteboard or shared drawing tool available for Stage 1.
- [ ] For Stage 2: candidate has been briefed to **bring their own IDE and ready-to-code project** (e.g. bootstrapped app, no application code yet); they will screen share. Have ACs to share. You do not provide a coding environment.
- [ ] For Stage 3: "bad" PR ready (concrete repository/DB, no interface, partial unit tests); repo or patch they can comment on.
- [ ] Agreement on who leads each stage and who takes notes on "what to look for."
